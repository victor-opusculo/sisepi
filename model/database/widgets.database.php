<?php

require_once("database.php");


function getNextEvents($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "select events.id, events.name as eventName, eventdates.date, eventdates.name
from eventdates
inner join events on events.id = eventdates.eventId
where eventdates.date >= curdate() and eventdates.date <= date_add(curdate(), interval 10 day)
order by eventdates.date asc
limit 8";

	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
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

function getLatestProfessors($optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "select id, aes_decrypt(name, '$__cryptoKey') as name, registrationDate
from professors
order by registrationDate desc
limit 8";

	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
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