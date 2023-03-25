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

function getNextChecklists($currentUserId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query1 = "SELECT events.id, events.name, min(eventdates.date) as beginDate, events.checklistId, eventchecklists.finalized as checklistFinalized, eventchecklists.checklistJson FROM events
	LEFT JOIN eventdates ON eventdates.eventId = events.id
	LEFT JOIN eventchecklists ON eventchecklists.id = events.checklistId
	Where CURRENT_DATE() <= eventdates.date AND
	date_add(CURRENT_DATE(), interval 15 day) >= eventdates.date AND
	json_contains(json_extract(eventchecklists.checklistJson, '$.preevent[*].responsibleUser', '$.preevent[*].checkList[*].responsibleUser'), ?)
	group by events.id";

	$query2 = "SELECT eventdates.id as eventDateId, events.id as id, events.name, eventdates.name as eventDateName, eventdates.date as beginDate, eventdates.checklistId, eventchecklists.finalized as checklistFinalized, eventchecklists.checklistJson FROM eventdates
	LEFT JOIN events ON events.id = eventdates.eventId
	LEFT JOIN eventchecklists ON eventchecklists.id = eventdates.checklistId
	Where CURRENT_DATE() <= eventdates.date AND
	date_add(CURRENT_DATE(), interval 3 day) >= eventdates.date AND
	NOT eventchecklists.finalized AND
	json_contains(json_extract(eventchecklists.checklistJson, '$.eventdate[*].responsibleUser', '$.eventdate[*].checkList[*].responsibleUser'), ?)";

	$query3 = "SELECT events.id, events.name, max(eventdates.date) as endDate, events.checklistId, eventchecklists.finalized as checklistFinalized, eventchecklists.checklistJson FROM events
	LEFT JOIN eventdates ON eventdates.eventId = events.id
	LEFT JOIN eventchecklists ON eventchecklists.id = events.checklistId
	Where CURRENT_DATE() >= eventdates.date AND
    NOT eventchecklists.finalized AND
	json_contains(json_extract(eventchecklists.checklistJson, '$.postevent[*].responsibleUser', '$.postevent[*].checkList[*].responsibleUser'), ?)
	group by events.id";

	$nextChecklistsReport = [ 'preevent' => null, 'eventdate' => null, 'postevent' => null ];

	function getDataRows($conn, $query, $currentUserId)
	{
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $currentUserId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$drs = $result->fetch_all(MYSQLI_ASSOC);
		$result->close();
		return $drs;
	}
	
	$nextChecklistsReport['preevent'] = getDataRows($conn, $query1, $currentUserId);
	$nextChecklistsReport['eventdate'] = getDataRows($conn, $query2, $currentUserId);
	$nextChecklistsReport['postevent'] = getDataRows($conn, $query3, $currentUserId);

	if (!$optConnection) $conn->close();

	return $nextChecklistsReport;
}

function getPendingProfessorWorkProposals(?mysqli $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "SELECT pwp.id, pwp.name, pwp.ownerProfessorId, pwp.registrationDate, aes_decrypt(professors.name, '$__cryptoKey') AS professorName FROM professorworkproposals AS pwp
	LEFT JOIN professors ON professors.id = pwp.ownerProfessorId
	WHERE pwp.isApproved IS NULL
	LIMIT 8";
	$result = $conn->query($query);
	$dataRows = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : null;
	$result->close();

	if (!$optConnection) $conn->close();
	return $dataRows;
}