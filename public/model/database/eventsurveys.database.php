<?php
require_once("database.php");
require_once("generalsettings.database.php");

function getEventBasicInfos($eventId, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT events.id, events.name, events.subscriptionListNeeded, events.surveyTemplateId, 
    min(eventdates.date) as 'beginDate', max(eventdates.date) as 'endDate', 
    enums.value as 'typeName'
    FROM events
    inner join eventdates on eventdates.eventId = events.id 
    left join enums on enums.type = 'EVENT' and enums.id = events.typeId
    where events.id = ? 
    group by events.id ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRow;
}

function getSurveyTemplateJson($surveyTemplateId, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT templateJson from jsontemplates where type = 'eventsurvey' and id = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $surveyTemplateId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $templateJson = $result->num_rows > 0 ? $result->fetch_row()[0] : null; 
    $result->close();

    if (!$optConnection) $conn->close();
    return $templateJson;
}

function getStudentPresencePercent($email, $eventId, $eventsRequiresSubscriptionList, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();
    $__cryptoKey = getCryptoKey();

    $querySl = "SELECT floor((count(presencerecords.subscriptionId) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
	from presencerecords
	inner join subscriptionstudents on subscriptionstudents.id = presencerecords.subscriptionId
	where presencerecords.eventId = ? and subscriptionstudents.email = aes_encrypt(lower(?), '$__cryptoKey')
    group by presencerecords.subscriptionId";

    $queryNsl = "SELECT floor((count(presencerecords.email) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
	from presencerecords
	where presencerecords.eventId = ? and subscriptionId is null and presencerecords.email = aes_encrypt(lower(?), '$__cryptoKey')
	group by presencerecords.email";

    $query = $eventsRequiresSubscriptionList ? $querySl : $queryNsl;
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $eventId, $eventId, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $presencePercent = $result->num_rows > 0 ? $result->fetch_row()[0] : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $presencePercent;
}

function getSubscriptionId($eventId, $email, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();
    $__cryptoKey = getCryptoKey();

    $query = "SELECT id from subscriptionstudents where eventId = ? and email = aes_encrypt(lower(?), '$__cryptoKey') ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $eventId, $email);
    $stmt->execute();
    $result =  $stmt->get_result();
    $stmt->close();
    $subsId = $result->num_rows > 0 ? $result->fetch_row()[0] : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $subsId;
}

function checkForExistentAnswer($eventId, $subscriptionId, $studentEmail, $eventsRequiresSubscriptionList, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();
    $__cryptoKey = getCryptoKey();

    $query = null;
    $bindParamTypes = null;
    $bindParamValues = null;
    if ((bool)$eventsRequiresSubscriptionList)
    {
        $query = "SELECT id from eventsurveys where eventId = ? and subscriptionId = ? and studentEmail is null ";
        $bindParamTypes = "ii";
        $bindParamValues = [ $eventId, $subscriptionId ];
    }
    else
    {
        $query = "SELECT id from eventsurveys where eventId = ? and subscriptionId is null and studentEmail = aes_encrypt(lower(?), '$__cryptoKey') ";
        $bindParamTypes = "is";
        $bindParamValues = [ $eventId, $studentEmail ];
    }
    $stmt = $conn->prepare($query);
    $stmt->bind_param($bindParamTypes, ...$bindParamValues);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $exists = $result->num_rows > 0;
    $result->close();

    if (!$optConnection) $conn->close();
    return $exists;
}

function saveSurveyAnswer($responseSurveyObject, $studentEmail, $eventId, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();
    $__cryptoKey = getCryptoKey();

    $eventInfos = (object)getEventBasicInfos($eventId, $conn);
    $finalSurveyObject = applySurveyResponse($responseSurveyObject, json_decode(getSurveyTemplateJson($eventInfos->surveyTemplateId, $conn)));

    $studentPresencePercent = getStudentPresencePercent($studentEmail, $eventId, $eventInfos->subscriptionListNeeded, $conn);
    if (is_null($studentPresencePercent))
        throw new Exception("E-mail não localizado nas inscrições ou listas de presença.");
    
    if ($studentPresencePercent < readSetting('STUDENTS_MIN_PRESENCE_PERCENT', $conn))
        throw new Exception("Você não atingiu a frequência mínima exigida para aprovação.");

    $studentSubscriptionId = null;
    $query = null;
    $bindParamTypes = null;
    $bindParamValues = null;

    if ((bool)$eventInfos->subscriptionListNeeded)
    {
        $studentSubscriptionId = getSubscriptionId($eventId, $studentEmail, $conn);
        if (checkForExistentAnswer($eventId, $studentSubscriptionId, null, true, $conn)) throw new Exception("Você já respondeu a pesquisa de satisfação para este evento.");
        $query = "INSERT into eventsurveys (eventId, subscriptionId, studentEmail, surveyJson, registrationDate) values (?, ?, null, ?, NOW()) ";
        $bindParamTypes = "iis";
        $bindParamValues = [ $eventId, $studentSubscriptionId, json_encode($finalSurveyObject) ];
    }
    else
    {
        if (checkForExistentAnswer($eventId, null, $studentEmail, false, $conn)) throw new Exception("Você já respondeu a pesquisa de satisfação para este evento.");
        $query = "INSERT into eventsurveys (eventId, subscriptionId, studentEmail, surveyJson, registrationDate) values (?, null, aes_encrypt(lower(?), '$__cryptoKey'), ?, NOW()) ";
        $bindParamTypes = "iss";
        $bindParamValues = [ $eventId, $studentEmail, json_encode($finalSurveyObject) ];
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param($bindParamTypes, ...$bindParamValues);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $newId = $conn->insert_id;
    $stmt->close();

    if (!$optConnection) $conn->close();
    return [ 'isCreated' => $affectedRows > 0, 'newId' => $newId ];
}

function applySurveyResponse($responseSurveyObject, $templateSurveyObject)
{
    $output = clone $templateSurveyObject;

    function apply($responseObject, $blockName, &$output)
    {
        foreach ($responseObject->$blockName as $index => $itemValue)
        {
            if (!is_array($itemValue)) //Not a checklist
                $output->$blockName[$index]->value = $itemValue;
            else
            {
                //A checklist
                foreach ($responseObject->$blockName[$index] as $subIndex => $subValue)
                    $output->$blockName[$index]->checkList[$subIndex]->value = $subValue;
            }
        }
    }

    if ($responseSurveyObject->head)
       apply($responseSurveyObject, 'head', $output);

    if ($responseSurveyObject->body)
       apply($responseSurveyObject, 'body', $output);

    if ($responseSurveyObject->foot)
       apply($responseSurveyObject, 'foot', $output);

    return $output;
}