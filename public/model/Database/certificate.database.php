<?php

require_once("database.php");


//Get single event
function getSingleEvent($id , $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$row = null;
	if($stmt = $conn->prepare("select events.*, SEC_TO_TIME( SUM( TIME_TO_SEC( TIMEDIFF( eventdates.endTime, eventdates.beginTime ) ) ) ) AS 'hours' 
	from events 
	INNER JOIN eventdates ON eventdates.eventId = events.id 
	where events.id = ?"))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $row;
}

//Get event dates
function getEventDates($eventId , $optConnection = null)
{	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	
	if($stmt = $conn->prepare("SELECT eventdates.*, (concat(eventdates.date, ' ', eventdates.beginTime)) as fullDateTime
	FROM eventdates
	WHERE eventdates.eventId = ? 
	ORDER BY fullDateTime ASC"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
			{
				array_push($dataRows, $row);
			}
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function isEventOver($eventId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "SELECT (curdate() >= max(eventdates.date)) as isEventOver
FROM eventdates
WHERE eventdates.eventId = ?";

	$isOver = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$isOver = (boolean)$result->fetch_row()[0];
	}

	if (!$optConnection) $conn->close();
	return $isOver;
}

function getStudentData($eventId, $isSubscriptionEnabled, $email, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$__cryptoKey = getCryptoKey();

	$query = "";
	if ($isSubscriptionEnabled)
		$query = "SELECT presencerecords.subscriptionId, 
		aes_decrypt(subscriptionstudentsnew.name, '$__cryptoKey') as name,
		aes_decrypt(subscriptionstudentsnew.email, '$__cryptoKey') as email, 
		aes_decrypt(subscriptionstudentsnew.subscriptionDataJson, '$__cryptoKey') as subscriptionDataJson, 
		floor((count(presencerecords.subscriptionId) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
from presencerecords
inner join subscriptionstudentsnew on subscriptionstudentsnew.id = presencerecords.subscriptionId
where presencerecords.eventId = ? and subscriptionstudentsnew.email = aes_encrypt(lower(?), '$__cryptoKey')
group by presencerecords.subscriptionId;";
	else
		$query = "SELECT aes_decrypt(presencerecords.name, '$__cryptoKey') as name, aes_decrypt(presencerecords.email, '$__cryptoKey') as email,  floor((count(presencerecords.email) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
from presencerecords
where presencerecords.eventId = ? and subscriptionId is null and presencerecords.email = aes_encrypt(lower(?), '$__cryptoKey')
group by presencerecords.email;";
	
	$dataRow = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("iis", $eventId, $eventId, $email);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$dataRow = $result->fetch_assoc();
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function authenticateCertificate($id, $issueDateTime, $optConnection = null)
{	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$__cryptoKey = getCryptoKey();
	
	$query = "select certificates.id, certificates.eventId, certificates.dateTime, aes_decrypt(certificates.email, '$__cryptoKey') as email, events.name as eventName
from certificates
left join events on events.id = certificates.eventId
where certificates.id = ? and certificates.dateTime = ?;";

	$dataRow = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("is", $id, $issueDateTime);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$dataRow = $result->fetch_assoc();
	}

	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function isCertificateAlreadyIssued($eventId, $email, $optConnection = null)
{	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$__cryptoKey = getCryptoKey();
	
	$query = "SELECT * FROM certificates WHERE eventId = ? AND email = aes_encrypt(lower(?), '$__cryptoKey')";

	$dataRow = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("is", $eventId, $email);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$dataRow = $result->fetch_assoc();
	}

	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function saveCertificateInfos($eventId, $dateTime, $email, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$__cryptoKey = getCryptoKey();
	
	$query = "INSERT INTO certificates (eventId, dateTime, email) VALUES (?, ?, aes_encrypt(lower(?), '$__cryptoKey'))";
	$insertedId = null;
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("iss", $eventId, $dateTime, $email);
		$stmt->execute();
		$stmt->close();
		
		$insertedId = $conn->insert_id;
	}

	if (!$optConnection) $conn->close();
	
	return $insertedId;
}

function searchCertificates($email, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$__cryptoKey = getCryptoKey();

	$query = "SELECT events.id, events.name, enums.value as eventType
	from events
	left join enums on enums.type = 'EVENT' and enums.id = events.typeId
	where (CASE
	when events.subscriptionListNeeded = 1 then 
		   (select floor((count(presencerecords.subscriptionId) / (select count(*) from eventdates where eventId = events.id and presenceListNeeded = 1)) * 100) as presencePercent
			from presencerecords
			inner join subscriptionstudentsnew on subscriptionstudentsnew.id = presencerecords.subscriptionId
			where presencerecords.eventId = events.id and subscriptionstudentsnew.email = aes_encrypt(lower(?), '$__cryptoKey')
			group by presencerecords.subscriptionId) >= (select value from settings where name = 'STUDENTS_MIN_PRESENCE_PERCENT') 
			AND (select max(eventdates.date) from eventdates where eventdates.eventId = events.id) <= CURRENT_DATE() 
			AND (events.certificateText IS NOT NULL AND events.certificateText <> '')
			AND (events.testTemplateId IS NULL OR 
				(events.testTemplateId IS NOT NULL AND 
					(SELECT (JSON_EXTRACT(testData, '$.grade') >= JSON_EXTRACT(testData, '$.percentForApproval') OR COUNT(eventcompletedtests.id) = 0)
					FROM eventcompletedtests
					INNER JOIN subscriptionstudentsnew ON subscriptionstudentsnew.id = eventcompletedtests.subscriptionId
					WHERE subscriptionstudentsnew.email = AES_ENCRYPT(lower(?), '$__cryptoKey')
						AND eventcompletedtests.eventId = events.id)
				)
			) 
	when events.subscriptionListNeeded = 0 THEN 
			(select floor((count(presencerecords.email) / (select count(*) from eventdates where eventId = events.id and presenceListNeeded = 1)) * 100) as presencePercent
			from presencerecords
			where presencerecords.eventId = events.id and subscriptionId is null and presencerecords.email = aes_encrypt(lower(?), '$__cryptoKey')
			group by presencerecords.email) >= (select value from settings where name = 'STUDENTS_MIN_PRESENCE_PERCENT') 
			AND (select max(eventdates.date) from eventdates where eventdates.eventId = events.id) <= CURRENT_DATE() 
			AND (events.certificateText IS NOT NULL AND events.certificateText <> '')
			AND (events.testTemplateId IS NULL OR
				(events.testTemplateId IS NOT NULL AND
					(SELECT (JSON_EXTRACT(testData, '$.grade') >= JSON_EXTRACT(testData, '$.percentForApproval') OR COUNT(eventcompletedtests.id) = 0)
					FROM eventcompletedtests
					WHERE eventcompletedtests.email = AES_ENCRYPT(lower(?), '$__cryptoKey')
						AND eventcompletedtests.eventId = events.id)
				)
			) 
	END)
	ORDER BY name ASC";

	$stmt = $conn->prepare($query);
	$stmt->bind_param("ssss", $email, $email, $email, $email);
	$stmt->execute();
	$result = $stmt->get_result();
	$dataRows = null;
	if ($result->num_rows > 0)
		$dataRows = $result->fetch_all(MYSQLI_ASSOC);
	$stmt->close();
	$result->close();

	if (!$optConnection) $conn->close();

	return $dataRows;
}

function checkForExistentSurveyAnswer($eventId, $subscriptionId, $studentEmail, $eventsRequiresSubscriptionList, $optConnection = null)
{
	require_once ("eventsurveys.database.php");
	return checkForExistentAnswer($eventId, $subscriptionId, $studentEmail, $eventsRequiresSubscriptionList, $optConnection);
}