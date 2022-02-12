<?php
//Public
require_once("database.php");
require_once("crypto.php");

function getEventsCount($searchKeywords)
{
	$conn = createConnectionAsEditor();
	
	$totalRecords = 0;
	if (strlen($searchKeywords) > 3)
	{
		$query = "SELECT count(*) 
		FROM events
		where match (events.name, events.moreInfos) against (?)";
		if($stmt = $conn->prepare($query))
		{
			$stmt->bind_param("s", $searchKeywords);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			
			$totalRecords = $result->fetch_row()[0];
		}
	}
	else
		$totalRecords = $conn->query('select count(*) from events')->fetch_row()[0];
	
	$conn->close();
	
	return $totalRecords;
}

function getSubscriptionCount($eventId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$totalRecords = 0;
	if ($stmt = $conn->prepare('select count(*) from subscriptionstudents where eventId = ?'))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$totalRecords = $stmt->get_result()->fetch_row()[0];
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $totalRecords;
}

//Get single event with type name
function getSingleEvent($id , $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$row = null;
	if($stmt = $conn->prepare("select events.*, enums.value as 'typeName', SEC_TO_TIME( SUM( TIME_TO_SEC( TIMEDIFF( eventdates.endTime, eventdates.beginTime ) ) ) ) AS 'hours' 
	from events 
	INNER JOIN eventdates ON eventdates.eventId = events.id 
	right join enums on enums.type = 'EVENT' and enums.id = events.typeId 
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

function getEventsPartially($page, $numResultsOnPage, $_orderBy, $searchKeywords)
{
	$orderBy = ($_orderBy === null || $_orderBy === "") ? "date" : $_orderBy;
	
	$conn = createConnectionAsEditor();
	
	$queryBase = "SELECT events.id, events.name, MIN(eventdates.date) AS date, SEC_TO_TIME( SUM( TIME_TO_SEC( TIMEDIFF( endTime, beginTime ) ) ) ) AS 'hours', enums.value AS 'typeName'
FROM `events`
INNER JOIN eventdates ON eventdates.eventId = events.id
INNER JOIN enums ON enums.type = 'EVENT' AND enums.id = events.typeId ";

	$queryWhere = "";
	if (strlen($searchKeywords) > 3)
		$queryWhere = "WHERE MATCH (events.name, events.moreInfos) AGAINST (?) ";
	
	$queryGroupBy = "GROUP BY events.id, events.name ";
	
	$queryOrderBy = "";
	if ($orderBy === "date")
		$queryOrderBy = "ORDER BY eventdates.date DESC ";
	else
		$queryOrderBy = "ORDER BY events.name ASC ";
	
	$queryLimit = "LIMIT ? , ?";
	
	$dataRows = null;
	$query = $queryBase . $queryWhere . $queryGroupBy . $queryOrderBy . $queryLimit;
		
	if($stmt = $conn->prepare($query))
	{
		$calc_page = ($page - 1) * $numResultsOnPage;
		if (strlen($searchKeywords) > 3)
			$stmt->bind_param("sii", $searchKeywords, $calc_page, $numResultsOnPage);
		else
			$stmt->bind_param("ii", $calc_page, $numResultsOnPage);
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
	
	$conn->close();
	
	return $dataRows;
}

//Get event dates with professor name
function getEventDates($eventId , $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	
	if($stmt = $conn->prepare("SELECT eventdates.*, (concat(eventdates.date, ' ', eventdates.beginTime)) as fullDateTime, aes_decrypt(professors.name, '$__cryptoKey') as 'professorName', (CONCAT( DATE, ' ', beginTime ) < NOW() AND DATE_ADD( CONCAT( DATE, ' ', endTime ) , INTERVAL 30 MINUTE) > NOW()) AS isPresenceListOpen 
	FROM eventdates 
	LEFT JOIN professors ON (eventdates.professorId = professors.id) 
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

function getEventAttachments($eventId , $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	
	if($stmt = $conn->prepare("select * from eventattachments where eventId = ?"))
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

function getFullEvent($id)
{
	$conn = createConnectionAsEditor();
	
	$singleEventDataRows = getSingleEvent($id, $conn);
	$currentSubscriptionNumber = getSubscriptionCount($id, $conn);
	$eventdatesDataRows = getEventDates($id, $conn);
	$eventAttachmentsDataRows = getEventAttachments($id, $conn);
	
	$conn->close();
	
	$output = [];
	$output["event"] = $singleEventDataRows;
	$output["eventdates"] = $eventdatesDataRows;
	$output["eventattachments"] = $eventAttachmentsDataRows;
	
	$output["currentSubscriptionNumber"] = $currentSubscriptionNumber;
	
	return $output;
}