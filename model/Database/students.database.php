<?php
require_once("database.php");

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

function getSubscriptionsCount($eventId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$totalRecords = 0;
	if($stmt = $conn->prepare("select count(*) from subscriptionstudentsnew where eventid = ?"))
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

function getSubscriptionList($eventId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	if($stmt = $conn->prepare("SELECT id, eventId, 
	aes_decrypt(name, '$__cryptoKey') as name,
	aes_decrypt(email, '$__cryptoKey') as email,
	aes_decrypt(subscriptionDataJson, '$__cryptoKey') as subscriptionDataJson,
	subscriptionDate 
	FROM subscriptionstudentsnew where eventId = ? order by subscriptionDate asc"))
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

function getSingleSubscription($id, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	if($stmt = $conn->prepare("SELECT id, eventId, 
	aes_decrypt(name, '$__cryptoKey') as name,
	aes_decrypt(email, '$__cryptoKey') as email,
	aes_decrypt(subscriptionDataJson, '$__cryptoKey') as subscriptionDataJson,
	subscriptionDate
	from subscriptionstudentsnew where id = ?"))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$results = $stmt->get_result();
		$stmt->close();
		
		if ($results->num_rows > 0)
			$dataRow = $results->fetch_assoc();
		else
		{
			if (!$optConnection) $conn->close();
			throw new Exception("Inscrição não localizada.");
		}
	}
	
	if (!$optConnection) $conn->close();
	return $dataRow;
}

function getAllSubscriptions($eventId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$dataRows = null;
	if($stmt = $conn->prepare("SELECT id,
	aes_decrypt(name, '$__cryptoKey') as 'name', 
	aes_decrypt(email, '$__cryptoKey') as 'email',
	aes_decrypt(subscriptionDataJson, '$__cryptoKey') as 'subscriptionDataJson', 
	subscriptionDate
	from subscriptionstudentsnew where eventId = ?"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$results = $stmt->get_result();
		$stmt->close();
		
		if ($results->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $results->fetch_assoc())
				$dataRows[] = $row;
		}	
		$results->close();	
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function doesSubscriptionExists($email, $eventId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$exists = false;
	$query = "SELECT count(id) FROM subscriptionstudentsnew WHERE email = aes_encrypt(lower(?), '$__cryptoKey') AND eventId = ?";
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("si", $email, $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$exists = (bool)$result->fetch_row()[0];
	}

	if (!$optConnection) $conn->close();
	return $exists;
}

function createSubscription($postData, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	if (doesSubscriptionExists($postData['txtEmail'], $postData['eventId'], $conn))
		throw new Exception("Inscrição já existente para este evento");

	$affectedRows = 0;
	$newId = null;
	$query = "INSERT INTO subscriptionstudentsnew (eventId, name, email, subscriptionDataJson, subscriptionDate) 
	VALUES (?, aes_encrypt(?, '$__cryptoKey'), aes_encrypt(lower(?), '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'), NOW())";

	if ($stmt = $conn->prepare($query))
	{
		$finalJson = json_encode(applySubscriptionDataToJsonObject($postData, json_decode(getEventsSubscriptionTemplate($postData['eventId'], $conn))));
		$stmt->bind_param("isss", $postData['eventId'], $postData['txtName'], $postData['txtEmail'], $finalJson);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$newId = $conn->insert_id;
		$stmt->close();
	}

	if (!$optConnection) $conn->close();

	return [ 'newId' => $newId, 'isCreated' => $affectedRows > 0 ];
}

function getEventsSubscriptionTemplate($eventId, ?mysqli $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "SELECT events.subscriptionTemplateId, jsontemplates.templateJson from events 
	INNER JOIN jsontemplates ON jsontemplates.id = events.subscriptionTemplateId AND jsontemplates.type = 'eventsubscription' 
	WHERE events.id = ? ";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('i', $eventId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$json = $result->num_rows > 0 ? $result->fetch_assoc()['templateJson'] : null;
	$result->close();

	if (!$optConnection) $conn->close();
	return $json;
}

function updateSubscription($postData, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$stmt = $conn->prepare("SELECT AES_DECRYPT(subscriptionDataJson, '$__cryptoKey') FROM subscriptionstudentsnew WHERE id = ? ");
	$stmt->bind_param('i', $postData['subscriptionId']);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$template = $result->num_rows > 0 ? $result->fetch_row()[0] : null;
	$result->close();

	$affectedRows = 0;
	$query = "UPDATE subscriptionstudentsnew 
	SET name = aes_encrypt(?, '$__cryptoKey'), 
	email = aes_encrypt(?, '$__cryptoKey'),
	subscriptionDataJson = aes_encrypt(?, '$__cryptoKey') 
	WHERE id = ?";
	
	if($stmt = $conn->prepare($query))
	{
		$finalJson = json_encode(applySubscriptionDataToJsonObject($postData, json_decode($template)));
		$stmt->bind_param("sssi", $postData["txtName"], $postData["txtEmail"], $finalJson, $postData["subscriptionId"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function applySubscriptionDataToJsonObject(array $arrayFromPost, object $subscriptionDataTemplate)
{
	if (empty($subscriptionDataTemplate)) return null;

	$output = clone $subscriptionDataTemplate;
	if (isset($arrayFromPost['questions']))
		foreach ($arrayFromPost['questions'] as $i => $value)
		{
			$output->questions[$i]->value = $value;
		}

	if (isset($arrayFromPost['terms']))
		foreach ($arrayFromPost['terms'] as $i => $value)
		{
			$output->terms[$i]->value = $value;
		}

	return $output;
}

function deleteSubscription($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$success = false;
	if($stmt = $conn->prepare("delete from subscriptionstudentsnew where id = ?"))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$success = $stmt->affected_rows > 0;
		$stmt->close();
	}
	
	deletePresenceRecordFromSubscriptionId($id, $conn);
	
	if (!$optConnection) $conn->close();
	
	return $success;
}

include_once("generalsettings.database.php");

function getPresenceAppointment($eventId, $approvedOnly = false, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "";
	if ($approvedOnly)
	{
		$query = "select * 
		from 
			(select presencerecords.subscriptionId, 
			presencerecords.eventId,
			eventcompletedtests.id as testId,
			aes_decrypt(subscriptionstudentsnew.name, '$__cryptoKey') as name , 
			aes_decrypt(subscriptionstudentsnew.subscriptionDataJson, '$__cryptoKey') as subscriptionDataJson, 
			aes_decrypt(subscriptionstudentsnew.email, '$__cryptoKey') as email, 
			floor((count(presencerecords.subscriptionId) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent,
			JSON_EXTRACT(eventcompletedtests.testData, '$.grade') AS testGrade,
			JSON_EXTRACT(eventcompletedtests.testData, '$.percentForApproval') AS percentForApproval
from presencerecords
inner join subscriptionstudentsnew on subscriptionstudentsnew.id = presencerecords.subscriptionId
left join eventcompletedtests ON eventcompletedtests.subscriptionId = presencerecords.subscriptionId
where presencerecords.eventId = ?
group by presencerecords.subscriptionId) as presencesTable where presencePercent >= " . readSetting('STUDENTS_MIN_PRESENCE_PERCENT', $conn) . "
AND (
		(
			(SELECT testTemplateId IS NOT NULL FROM events WHERE events.id = presencesTable.eventId)
			AND presencesTable.testGrade >= presencesTable.percentForApproval
		) OR 
			(SELECT testTemplateId IS NULL FROM events WHERE events.id = presencesTable.eventId)
	);";
	}
	else
	{
		$query = "select subscriptionstudentsnew.id as subscriptionId,
		eventcompletedtests.id as testId,
		aes_decrypt(subscriptionstudentsnew.name, '$__cryptoKey') as name,
		aes_decrypt(subscriptionstudentsnew.subscriptionDataJson, '$__cryptoKey') as subscriptionDataJson,
		aes_decrypt(subscriptionstudentsnew.email, '$__cryptoKey') as email,
		floor((count(presencerecords.subscriptionId) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent,
		JSON_EXTRACT(eventcompletedtests.testData, '$.grade') AS testGrade,
		JSON_EXTRACT(eventcompletedtests.testData, '$.percentForApproval') AS percentForApproval
from subscriptionstudentsnew
left join presencerecords on presencerecords.subscriptionId = subscriptionstudentsnew.id
left join eventcompletedtests ON eventcompletedtests.subscriptionId = subscriptionstudentsnew.id
where subscriptionstudentsnew.eventId = ?
group by subscriptionstudentsnew.id;";
	}
	
	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("ii", $eventId, $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
				array_push($dataRows, $row);
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getPresenceAppointmentNoSubs($eventId, $approvedOnly = false, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "";
	if ($approvedOnly)
	{
		$query = "select * 
		from 
		(select 
			presencerecords.eventId,
			eventcompletedtests.id as testId,
			aes_decrypt(presencerecords.name, '$__cryptoKey') as name,
			aes_decrypt(presencerecords.email, '$__cryptoKey') as email, 
			floor((count(presencerecords.email) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent,
			JSON_EXTRACT(eventcompletedtests.testData, '$.grade') AS testGrade,
			JSON_EXTRACT(eventcompletedtests.testData, '$.percentForApproval') AS percentForApproval
from presencerecords
left join eventcompletedtests ON eventcompletedtests.email = presencerecords.email AND eventcompletedtests.eventId = presencerecords.eventId
where presencerecords.eventId = ? and presencerecords.subscriptionId is null 
group by email) as presencesTable where presencePercent >= " . readSetting('STUDENTS_MIN_PRESENCE_PERCENT', $conn) . "
AND (
		(
			(SELECT testTemplateId IS NOT NULL FROM events WHERE events.id = presencesTable.eventId)
			AND presencesTable.testGrade >= presencesTable.percentForApproval
		) OR 
			(SELECT testTemplateId IS NULL FROM events WHERE events.id = presencesTable.eventId)
);";
	}
	else
	{
		$query = "select
			presencerecords.eventId,
			eventcompletedtests.id as testId,
			aes_decrypt(presencerecords.name, '$__cryptoKey') as name,
			aes_decrypt(presencerecords.email, '$__cryptoKey') as email,
			floor((count(presencerecords.email) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent,
			JSON_EXTRACT(eventcompletedtests.testData, '$.grade') AS testGrade,
			JSON_EXTRACT(eventcompletedtests.testData, '$.percentForApproval') AS percentForApproval
from presencerecords
left join eventcompletedtests ON eventcompletedtests.email = presencerecords.email AND eventcompletedtests.eventId = presencerecords.eventId
where presencerecords.eventId = ? and presencerecords.subscriptionId is null 
group by email;";
	}

	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("ii", $eventId, $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
				array_push($dataRows, $row);
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getEventBasicInfos($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	if($stmt = $conn->prepare(
	"SELECT events.id, events.name, events.subscriptionListNeeded, events.subscriptionListClosureDate, events.maxSubscriptionNumber, events.allowLateSubscriptions, events.posterImageAttachmentFileName, eventdates.date, enums.value AS 'typeName'
FROM `events`
INNER JOIN eventdates ON eventdates.eventId = events.id
RIGHT JOIN enums ON enums.type = 'EVENT' AND enums.id = events.typeId
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

//Get event date with professors name
function getEventDate($eventDateId , $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	
	if($stmt = $conn->prepare("SELECT eventdates.* , 
	(SELECT group_concat(aes_decrypt(professors.name, '$__cryptoKey') SEPARATOR ', ') from eventdatesprofessors left join professors on professors.id = eventdatesprofessors.professorId where eventDateId = ?) as 'professorsNames', 
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

function getPresenceList($eventDateId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "
SELECT presencerecords.id, aes_decrypt(subscriptionstudentsnew.name, '$__cryptoKey') as name, aes_decrypt(subscriptionstudentsnew.subscriptionDataJson, '$__cryptoKey') as subscriptionDataJson, aes_decrypt(subscriptionstudentsnew.email, '$__cryptoKey') as email
FROM presencerecords
INNER JOIN subscriptionstudentsnew ON subscriptionstudentsnew.id = presencerecords.subscriptionId
WHERE eventDateId = ?";

	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $eventDateId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
				array_push($dataRows, $row);
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getPresenceListNoSubs($eventDateId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "
SELECT presencerecords.id, aes_decrypt(presencerecords.name, '$__cryptoKey') as name, aes_decrypt(presencerecords.email, '$__cryptoKey') as email
FROM presencerecords
WHERE eventDateId = ?
AND subscriptionId IS NULL";

	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $eventDateId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
				array_push($dataRows, $row);
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getSinglePresenceRecord($id, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	if($stmt = $conn->prepare("SELECT presencerecords.id, presencerecords.eventId, presencerecords.eventDateId, presencerecords.subscriptionId , aes_decrypt(subscriptionstudentsnew.name, '$__cryptoKey') as name, aes_decrypt(subscriptionstudentsnew.subscriptionDataJson, '$__cryptoKey') as subscriptionDataJson, aes_decrypt(subscriptionstudentsnew.email, '$__cryptoKey') as email
FROM presencerecords
LEFT JOIN subscriptionstudentsnew ON subscriptionstudentsnew.id = presencerecords.subscriptionId
WHERE presencerecords.id = ?"))
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

function getSinglePresenceRecordNoSubs($id, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	if($stmt = $conn->prepare("SELECT id, eventId, eventDateId, subscriptionId, aes_decrypt(email, '$__cryptoKey') as email, aes_decrypt(name, '$__cryptoKey') as name
FROM presencerecords
WHERE id = ? and subscriptionId is null"))
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

function editSinglePresenceRecordNoSubs($postData, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$affectedRows = 0;
	
	if($stmt = $conn->prepare("UPDATE presencerecords SET name = aes_encrypt(?, '$__cryptoKey'), email = aes_encrypt(LOWER(?), '$__cryptoKey')
WHERE id = ?"))
	{
		$stmt->bind_param("ssi", $postData["txtName"], $postData["txtEmail"], $postData["presenceId"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $affectedRows > 0;
}

function deletePresenceRecord($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$success = false;
	if($stmt = $conn->prepare("delete from presencerecords where id = ?"))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$success = ($stmt->affected_rows === 1);
		$stmt->close(); 
	}
	
	if (!$optConnection) $conn->close();
	
	return $success;
}

function deletePresenceRecordFromSubscriptionId($subsId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$success = false;
	if($stmt = $conn->prepare("delete from presencerecords where subscriptionId = ?"))
	{
		$stmt->bind_param("i", $subsId);
		$stmt->execute();
		$success = ($stmt->affected_rows >= 1);
		$stmt->close(); 
	}
	
	if (!$optConnection) $conn->close();
	
	return $success;
}

function markPresence($postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "insert into presencerecords (eventId, eventDateId, subscriptionId) values (?, ?, ?)";
	
	$affectedRows = 0;
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("iii", $postData["eventId"], $postData["eventDateId"], $postData["selSubscriptionId"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function markPresenceNoSubs($postData, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "insert into presencerecords (eventId, eventDateId, email, name) values (?, ?, aes_encrypt(lower(?), '$__cryptoKey'), aes_encrypt(?, '$__cryptoKey'))";
	
	$affectedRows = 0;
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("iiss", $postData["eventId"], $postData["eventDateId"], $postData["txtEmail"], $postData["txtName"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
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
		$result->close();
	}
	
	if (!$optConnection) $conn->close();
	return $count > 0;
}

function checkIfPresenceIsSignedNoSubs($eventDateId, $email, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$count = 0;
	if($stmt = $conn->prepare("select count(*) from presencerecords where (eventDateId = ? and email = aes_encrypt(lower(?), '$__cryptoKey') )"))
	{
		$stmt->bind_param("is", $eventDateId, $email);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$count = $result->fetch_row()[0];
		$result->close();
	}
	
	if (!$optConnection) $conn->close();
	return $count > 0;
}

function getParticipationNumber($eventId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$stmt1 = $conn->prepare("select subscriptionListNeeded from events where id = ?");
	$stmt1->bind_param("i", $eventId);
	$stmt1->execute();
	$subscriptionEnabled = (bool)$stmt1->get_result()->fetch_row()[0];
	$stmt1->close();

	$count = 0;
	$query = "";
	if ($subscriptionEnabled)
		$query = "SELECT COUNT(DISTINCT(`subscriptionId`)) from presencerecords where eventId = ?";
	else
		$query = "SELECT COUNT(DISTINCT(`email`)) from presencerecords where eventId = ?";

	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$count = $result->fetch_row()[0];
		$stmt->close();
		$result->close();
	}

	if (!$optConnection) $conn->close();
	return $count;
}