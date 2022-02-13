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
		$query = "select presencerecords.subscriptionId, aes_decrypt(subscriptionstudents.name, '$__cryptoKey') as name , aes_decrypt(subscriptionstudents.socialName, '$__cryptoKey') as socialName, aes_decrypt(subscriptionstudents.email, '$__cryptoKey') as email, floor((count(presencerecords.subscriptionId) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
from presencerecords
inner join subscriptionstudents on subscriptionstudents.id = presencerecords.subscriptionId
where presencerecords.eventId = ? and subscriptionstudents.email = aes_encrypt(lower(?), '$__cryptoKey')
group by presencerecords.subscriptionId;";
	else
		$query = "select aes_decrypt(presencerecords.name, '$__cryptoKey') as name, aes_decrypt(presencerecords.email, '$__cryptoKey') as email,  floor((count(presencerecords.email) / (select count(*) from eventdates where eventId = ? and presenceListNeeded = 1)) * 100) as presencePercent
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
