<?php

require_once("database.php");

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

function getAllSurveyTemplates($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$result = $conn->query("SELECT * from jsontemplates where type = 'eventsurvey' ");
	$allRecords = $result->fetch_all(MYSQLI_ASSOC);
	$result->close();

	if (!$optConnection) $conn->close();
	return $allRecords;
}

function getEventSurveyTemplatesCount($searchKeywords, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$totalRecords = 0;
	$query = "SELECT count(*) FROM jsontemplates ";
	
	$infos = buildEventSurveyTemplatesQuery($query, $searchKeywords);
	
	$stmt = $conn->prepare($infos['query']);
	if (!empty($infos["bindParamTypes"]))
		$stmt->bind_param($infos['bindParamTypes'], ...$infos['bindParamParamsArray']);	
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	
	$totalRecords = $result->num_rows > 0 ? $result->fetch_row()[0] : 0;

	if (!$optConnection) $conn->close();
	return $totalRecords;
}

function buildEventSurveyTemplatesQuery($baseQuery, $searchKeywords, $useLimit = null)
{
	$outputInfos = [ "query" => "", "bindParamTypes" => "", "bindParamParamsArray" => [] ];
	
	$where = "where type = 'eventsurvey' ";
	
	if (strlen($searchKeywords) > 3)
	{	
		$outputInfos['bindParamTypes'] .= "s";
		$outputInfos['bindParamParamsArray'][] = $searchKeywords;
		$where .= "AND match(name) against (? in boolean mode) ";
	}
	
	$limit = "";
	if ($useLimit !== null)
	{
		$limit = "limit ?, ?";
		$outputInfos["bindParamTypes"] .= "ii";
		$outputInfos["bindParamParamsArray"][] = $useLimit[0];
		$outputInfos["bindParamParamsArray"][] = $useLimit[1];
	}
	
	$orderBy = "ORDER BY name ASC ";
	
	$outputInfos["query"] = $baseQuery . $where . $orderBy . $limit;
		
	return $outputInfos;
}

function getEventSurveyTemplatesPartially($page, $numResultsOnPage, $searchKeywords, $optConnection = null)
{
	$calc_page = ($page - 1) * $numResultsOnPage;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	$query = "SELECT id, name FROM jsontemplates ";
	$infos = buildEventSurveyTemplatesQuery($query, $searchKeywords, [ $calc_page, $numResultsOnPage ]);
	
	$stmt = $conn->prepare($infos['query']);
	if (!empty($infos["bindParamTypes"]))
		$stmt->bind_param($infos['bindParamTypes'], ...$infos['bindParamParamsArray']);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	
	if ($result->num_rows > 0) $dataRows = $result->fetch_all(MYSQLI_ASSOC);
	$result->close();

	if (!$optConnection) $conn->close();
	return $dataRows;
}

function getSingleSurveyTemplate($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$dataRow = null;
	$query = "SELECT id, name, templateJson FROM jsontemplates WHERE type = 'eventsurvey' AND id = ?";
	
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	if ($result->num_rows > 0) $dataRow = $result->fetch_assoc();
	$result->close();

	if (!$optConnection) $conn->close();

	return $dataRow;
}

function updateSingleSurveyTemplate($dbEntity, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$affectedRows = 0;
	$dbEntityInfos = $dbEntity->generateSQLUpdateCommandColumnsAndFields();
	$query = "update jsontemplates set $dbEntityInfos[setColumnsAndFields] where $dbEntityInfos[whereCondition]";

	$stmt = $conn->prepare($query);
	$bindParamInfos = $dbEntity->generateBindParamTypesAndValues();
	$stmt->bind_param($bindParamInfos['types'], ...$bindParamInfos['values']);
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();

	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function createSurveyTemplate($dbEntity, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$insertedId = null;
	$affectedRows = 0;

	$dbEntityInfos = $dbEntity->generateSQLCreateCommandColumnsAndFields();
	$query = "insert into jsontemplates ($dbEntityInfos[columns]) values ($dbEntityInfos[fields])";

	$stmt = $conn->prepare($query);
	$bindParamInfos = $dbEntity->generateBindParamTypesAndValues();
	$stmt->bind_param($bindParamInfos['types'], ...$bindParamInfos['values']); 
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();
		
	$insertedId = $conn->insert_id;

	if (!$optConnection) $conn->close();
	return [ 'isCreated' => $affectedRows > 0, 'newId' => $insertedId ];
}

function deleteSurveyTemplate($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$affectedRows = 0;
	$query1 = "DELETE from jsontemplates where type = 'eventsurvey' and id = ?";
	$stmt = $conn->prepare($query1);
	$stmt->bind_param("i", $id); 
	$stmt->execute();
	$affectedRows += $stmt->affected_rows;
	$stmt->close();

	$query2 = "UPDATE events set surveyTemplateId = NULL where surveyTemplateId = ?";
	$stmt = $conn->prepare($query2);
	$stmt->bind_param("i", $id); 
	$stmt->execute();
	$affectedRows += $stmt->affected_rows;
	$stmt->close();

	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function getAllAnsweredSurveysOfEvent($eventId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "SELECT * from eventsurveys where eventId = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $eventId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$surveysList = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
	$result->close();

	if (!$optConnection) $conn->close();
	return $surveysList;
}

function getSingleAnsweredSurvey($surveyId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "SELECT eventsurveys.*, events.name as 'eventName'
	from eventsurveys 
	inner join events on events.id = eventsurveys.eventId
	where eventsurveys.id = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $surveyId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$surveyDr = $result->num_rows > 0 ? $result->fetch_assoc() : null;
	$result->close();

	if (!$optConnection) $conn->close();
	return $surveyDr;
}

function deleteSingleAnsweredSurvey($surveyId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "DELETE from eventsurveys where id = ? ";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $surveyId);
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();

	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}