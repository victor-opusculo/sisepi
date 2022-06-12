<?php
//Public
require_once("database.php");


function getEventBasicInfos($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	if($stmt = $conn->prepare(
	"SELECT events.id, events.name, events.subscriptionListNeeded, events.subscriptionListClosureDate, events.subscriptionListOpeningDate, events.maxSubscriptionNumber, events.allowLateSubscriptions, events.posterImageAttachmentFileName, eventdates.date, enums.value AS 'typeName'
FROM `events`
INNER JOIN eventdates ON eventdates.eventId = events.id
RIGHT JOIN enums ON enums.type = 'EVENT' and enums.id = events.typeId
WHERE events.id = ?
GROUP BY events.id, events.name"))
	{
		
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$dataRow = $result->fetch_assoc();
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

//Get event date with professors names
function getEventDate($eventDateId , $optConnection = null)
{	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$__cryptoKey = getCryptoKey();
	
	$dataRow = null;
	
	if($stmt = $conn->prepare("SELECT eventdates.* , 
	(SELECT group_concat(aes_decrypt(professors.name, '$__cryptoKey') SEPARATOR ', ') FROM eventdatesprofessors LEFT JOIN professors ON professors.id = eventdatesprofessors.professorId Where eventdatesprofessors.eventDateId = ?) as professorsNames,
	(CONCAT( DATE, ' ', beginTime ) < NOW() AND DATE_ADD( CONCAT( DATE, ' ', endTime ) , INTERVAL 30 MINUTE) > NOW()) AS isPresenceListOpen 
	FROM eventdates 
	WHERE eventdates.id = ? "))
	{
		$stmt->bind_param("ii", $eventDateId, $eventDateId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRow = $result->fetch_assoc();
		}
		
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function getEventSubscriptionListInfos($eventId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	if($stmt = $conn->prepare("select id as eventId, name, subscriptionListNeeded, subscriptionListClosureDate, maxSubscriptionNumber from events where id = ?"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$results = $stmt->get_result();
		$stmt->close();
		
		$dataRow = $results->fetch_assoc();
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function getSubscriptionList($eventId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$__cryptoKey = getCryptoKey();
	
	$dataRows = null;
	if($stmt = $conn->prepare("select id, eventId, 
aes_decrypt(name, '$__cryptoKey') as name, aes_decrypt(socialName, '$__cryptoKey') as socialName, aes_decrypt(email, '$__cryptoKey') as email, aes_decrypt(telephone, '$__cryptoKey') as telephone, 
birthDate,
aes_decrypt(gender, '$__cryptoKey') as gender, aes_decrypt(schoolingLevel, '$__cryptoKey') as schoolingLevel, aes_decrypt(occupation, '$__cryptoKey') as occupation, aes_decrypt(accessibilityFeatureNeeded, '$__cryptoKey') as accessibilityFeatureNeeded,
agreesWithConsentForm, consentForm, subscriptionDate
from subscriptionstudents where eventId = ? order by subscriptionDate asc"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$results = $stmt->get_result();
		$stmt->close();
		
		$dataRows = [];
		while ($row = $results->fetch_assoc())
			array_push($dataRows, $row);
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getSubscriptionListOnlyNamesAndIds($eventId, $optConnection = null)
{	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$__cryptoKey = getCryptoKey();
	
	$dataRows = null;
	if($stmt = $conn->prepare("select id, aes_decrypt(name, '$__cryptoKey') as name, aes_decrypt(socialName,'$__cryptoKey') as socialName from subscriptionstudents where eventId = ? order by name asc"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$results = $stmt->get_result();
		$stmt->close();
		
		$dataRows = [];
		while ($row = $results->fetch_assoc())
			array_push($dataRows, $row);
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getSubscriptionsCount($eventId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$totalRecords = 0;
	if($stmt = $conn->prepare("select count(*) from subscriptionstudents where eventid = ?"))
	{
		
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
	
	$totalRecords = $result->fetch_row()[0];
	}
	
	if (!$optConnection) $conn->close();
	
	return $totalRecords;
}

function getEnum($enumType, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$list = null;
	if($stmt = $conn->prepare("select value as name from enums where type = ?"))
	{
		$stmt->bind_param("s", $enumType);
		$stmt->execute();
		$results = $stmt->get_result();
		$stmt->close();
		
		$list = [];
		while ($item = $results->fetch_row())
			$list[] = $item[0];
	}
	
	if (!$optConnection) $conn->close();
	
	return $list;
}

function getOccupationTypesAndIds($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$occupationTypes = [];
	$results = $conn->query('select id, value as name from enums where type = "OCCUPATION"');
	
	while ($ot = $results->fetch_assoc())
		array_push($occupationTypes, $ot);
	
	if (!$optConnection) $conn->close();
	
	return $occupationTypes;
}

function checkIfMailingContains($email, $optConnection = null)
{	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$__cryptoKey = getCryptoKey();
	
	$count = 0;
	if ($stmt = $results = $conn->prepare("select count(email) from mailing where email = aes_encrypt(lower(?), '$__cryptoKey')"))
	{
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$count = $stmt->get_result()->fetch_row()[0];
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $count > 0;
}

function formatNameCase($fullName)
{
	return mb_convert_case($fullName, MB_CASE_TITLE, "UTF-8");
}

function createMailingSubscriptionFromEventSubs($postData, $optConnection = null)
{	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$__cryptoKey = getCryptoKey();
	
	if($stmt = $conn->prepare("insert into mailing (email, name, eventId) values 
	(aes_encrypt(lower(?), '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), ?) "))
	{
		$name = $postData["txtSocialName"] ? formatNameCase($postData["txtSocialName"]) : formatNameCase($postData["txtName"]);
		
		$stmt->bind_param("ssi", 
		$postData["txtEmail"],
		$name,
		$postData["eventId"]);
		$stmt->execute();
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
}

function deleteMailingSubscriptionFromEventSubs($postData, $optConnection = null)
{	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$__cryptoKey = getCryptoKey();
	
	if (checkIfMailingContains($postData["txtEmail"], $conn))
	{
		if($stmt = $conn->prepare("delete from mailing where email = aes_encrypt(lower(?), '$__cryptoKey') "))
		{			
			$stmt->bind_param("s", $postData["txtEmail"]);
			$stmt->execute();
			$stmt->close();
		}
	}
	
	if (!$optConnection) $conn->close();
}

function checkIfSubscriptionsContain($eventId, $email, $optConnection = null)
{	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$__cryptoKey = getCryptoKey();
	
	$count = 0;
	if ($stmt = $results = $conn->prepare("select count(email) from subscriptionstudents where eventId = ? and email = aes_encrypt(lower(?), '$__cryptoKey')"))
	{
		$stmt->bind_param("is", $eventId, $email);
		$stmt->execute();
		$count = $stmt->get_result()->fetch_row()[0];
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $count > 0;
}

function createSubscription($postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$canCreate = true;
	$reasonForNotCreating = "";
	
	$subsCount = (int)getSubscriptionsCount($postData["eventId"], $conn);
	$eventBasicInfos = getEventBasicInfos($postData["eventId"], $conn);
	
	$isListEnabled = (boolean)$eventBasicInfos["subscriptionListNeeded"];
	$currentDate = new DateTime(date("Y-m-d"));
	$closureDate = new DateTime($eventBasicInfos["subscriptionListClosureDate"]);
	$maxSubsCount = (int)$eventBasicInfos["maxSubscriptionNumber"];
	
	if (!$isListEnabled)
	{
		$canCreate = false;
		$reasonForNotCreating = "Erro: Este evento não usa lista de inscrição.";
	}
	
	if ($subsCount >= $maxSubsCount)
	{
		$canCreate = false;
		$reasonForNotCreating = "Erro: Todas as vagas já estão preenchidas.";
	}
	
	if ($currentDate >= $closureDate)
	{
		$canCreate = false;
		$reasonForNotCreating = "Erro: As inscrições foram encerradas.";
	}
	
	if (checkIfSubscriptionsContain($postData["eventId"], $postData["txtEmail"], $conn))
	{
		$canCreate = false;
		$reasonForNotCreating = "Aviso: Você já se inscreveu neste evento.";
	}
	
	if ($canCreate)
	{
		$__cryptoKey = getCryptoKey();
		
		$newId = null;
		$affectedRows = 0;

		if($stmt = $conn->prepare("insert into subscriptionstudents (eventId, name, socialName, email, telephone, birthDate, gender, schoolingLevel, occupation, nationality, race, stateUf, accessibilityFeatureNeeded, agreesWithConsentForm, consentForm, subscriptionDate) values (?, 
		aes_encrypt(?, '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), aes_encrypt(lower(?), '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), 
		?,
		aes_encrypt(?, '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), 
		?, ?, now()) "))
		{
			$name = formatNameCase($postData["txtName"]);
			$socialName = formatNameCase($postData["txtSocialName"]);
			
			$stmt->bind_param("issssssssssssis", 
			$postData["eventId"],
			$name,
			$socialName,
			$postData["txtEmail"],
			$postData["txtTelephone"],
			$postData["dateBirthDate"],
			$postData["radGender"],
			$postData["radSchoolingLevel"],
			$postData["radOccupation"],
			$postData["radNationality"],
			$postData["radRace"],
			$postData["selUF"],
			$postData["txtAccessibilityRequired"],
			$postData["chkAgreesWithConsentForm"],
			$postData["consentFormLink"]);
			$stmt->execute();

			$affectedRows = $stmt->affected_rows;
			$newId = $conn->insert_id;

			$stmt->close();
			
			if (isset($postData["chkSubscribeMailing"]) && $postData["chkSubscribeMailing"])
				createMailingSubscriptionFromEventSubs($postData, $conn);
			else
				deleteMailingSubscriptionFromEventSubs($postData, $conn);
		}
		
		if (!$optConnection) $conn->close();
		
		return [ 'newId' => $newId, 'isCreated' => $affectedRows > 0 ];
	}
	else
	{
		if (!$optConnection) $conn->close();
		throw new Exception($reasonForNotCreating);
	}
}

function createLateSubscription($postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$canCreate = true;
	$reasonForNotCreating = "";
	
	$subsCount = (int)getSubscriptionsCount($postData["eventId"], $conn);
	$eventBasicInfos = getEventBasicInfos($postData["eventId"], $conn);
	
	$isListEnabled = (boolean)$eventBasicInfos["subscriptionListNeeded"];
	$maxSubsCount = (int)$eventBasicInfos["maxSubscriptionNumber"];
	
	if (!$isListEnabled)
	{
		$canCreate = false;
		$reasonForNotCreating = "Erro: Este evento não usa lista de inscrição.";
	}
	
	if ($subsCount >= $maxSubsCount)
	{
		$canCreate = false;
		$reasonForNotCreating = "Erro: Todas as vagas já estão preenchidas.";
	}
	
	if (checkIfSubscriptionsContain($postData["eventId"], $postData["txtEmail"], $conn))
	{
		$canCreate = false;
		$reasonForNotCreating = "Aviso: Você já se inscreveu neste evento.";
	}
	
	if ($canCreate)
	{
		$__cryptoKey = getCryptoKey();
		
		$success = false;
		if($stmt = $conn->prepare("insert into subscriptionstudents (eventId, name, socialName, email, agreesWithConsentForm, consentForm, subscriptionDate) values 
		(?, 
		aes_encrypt(?,'$__cryptoKey'), aes_encrypt(?,'$__cryptoKey'), aes_encrypt(lower(?),'$__cryptoKey'), 
		?, ?, now()) "))
		{
			$name = formatNameCase($postData["txtName"]);
			$socialName = formatNameCase($postData["txtSocialName"]);
			
			$stmt->bind_param("isssis", 
			$postData["eventId"],
			$name,
			$socialName,
			$postData["txtEmail"],
			$postData["chkAgreesWithConsentForm"],
			$postData["consentFormLink"]);
			$stmt->execute();
			
			$success = ($stmt->affected_rows === 1);
			
			$stmt->close();
			
		}
		
		if (!$optConnection) $conn->close();
		
		return $success;
	}
	else
	{
		if (!$optConnection) $conn->close();
		throw new Exception($reasonForNotCreating);
	}
}

function checkIfPresenceIsSigned($eventDateId, $subscriptionId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$count = 0;
	if($stmt = $conn->prepare("select count(*) from presencerecords where (eventDateId = ? and subscriptionId = ? )"))
	{
		$stmt->bind_param("ii", $eventDateId, $subscriptionId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		$count = $result->fetch_row()[0];
	}
	
	if (!$optConnection) $conn->close();
	
	return $count > 0;
}

function insertPresenceRecord($postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$canInsert = true;
	$reasonForNotInserting = "";
	
	if (checkIfPresenceIsSigned($postData["eventDateId"], $postData["selName"], $conn))
	{
		$canInsert = false;
		$reasonForNotInserting = "Você já assinou esta lista de presença.";
	}
	
	if ($canInsert)
	{
		if($stmt = $conn->prepare("insert into presencerecords (eventId, eventDateId, subscriptionId) values (?, ?, ?) "))
		{
			$stmt->bind_param("iii", $postData["eventId"], $postData["eventDateId"], $postData["selName"]);
			$stmt->execute();
			$stmt->close();
		}
		
		if (!$optConnection) $conn->close();
		
		return true;
	}
	else
	{
		if (!$optConnection) $conn->close();
		throw new Exception($reasonForNotInserting);
		return false;
	}
}

function checkIfPresenceIsSignedNoSubs($eventDateId, $email, $optConnection = null)
{	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$__cryptoKey = getCryptoKey();
	
	$count = 0;
	if($stmt = $conn->prepare("select count(*) from presencerecords where (eventDateId = ? and email = aes_encrypt(lower(?), '$__cryptoKey') )"))
	{
		$stmt->bind_param("is", $eventDateId, $email);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		$count = $result->fetch_row()[0];
	}
	
	if (!$optConnection) $conn->close();
	
	return $count > 0;
}

//No subscription list event - Insert presence record
function insertPresenceRecordNoSubs($postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$canInsert = true;
	$reasonForNotInserting = "";
	
	if (checkIfPresenceIsSignedNoSubs($postData["eventDateId"], $postData["txtEmail"], $conn))
	{
		$canInsert = false;
		$reasonForNotInserting = "Você já assinou esta lista de presença.";
	}
	
	if ($canInsert)
	{
		$__cryptoKey = getCryptoKey();
		
		if($stmt = $conn->prepare("insert into presencerecords (eventId, eventDateId, email, name) values 
		(?, ?, 
		aes_encrypt(lower(?), '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey')) "))
		{
			$name = formatNameCase($postData["txtName"]);
			
			$stmt->bind_param("iiss", $postData["eventId"], $postData["eventDateId"], $postData["txtEmail"], $name);
			$stmt->execute();
			$stmt->close();
		}
		
		if (!$optConnection) $conn->close();
		
		return true;
	}
	else
	{
		if (!$optConnection) $conn->close();
		throw new Exception($reasonForNotInserting);
		return false;
	}
}

function getLastSubscriptionByEmail($email, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$__cryptoKey = getCryptoKey();
	
	$query = "SELECT aes_decrypt(name, '$__cryptoKey') as name, 
aes_decrypt(socialName, '$__cryptoKey') as socialName,
aes_decrypt(telephone, '$__cryptoKey') as telephone,
birthDate,
aes_decrypt(gender, '$__cryptoKey') as gender,
aes_decrypt(schoolingLevel, '$__cryptoKey') as schoolingLevel,
aes_decrypt(occupation, '$__cryptoKey') as occupation,
aes_decrypt(nationality, '$__cryptoKey') as nationality,
aes_decrypt(race, '$__cryptoKey') as race,
aes_decrypt(stateUf, '$__cryptoKey') as stateUf,
aes_decrypt(accessibilityFeatureNeeded, '$__cryptoKey') as accessibilityFeatureNeeded,
subscriptionDate as subsDate
FROM subscriptionstudents
WHERE email = aes_encrypt(lower(?), '$__cryptoKey')
ORDER BY subsDate DESC
LIMIT 1";
	
	$dataRow = null;
	if($stmt = $conn->prepare($query))
	{		

		$stmt->bind_param("s", $email);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$dataRow = $result->fetch_assoc();
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}