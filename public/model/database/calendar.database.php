<?php
//public
require_once("database.php");

function getCertifiableEventsInMonth($month, $year, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
    $refDateTime = new DateTime("$year-$month-01");
    $firstDay = $refDateTime->format("Y-m-d");
    $lastDay = $refDateTime->format("Y-m-t");

	$query = "select events.name as eventName, eventdates.date, eventdates.name, eventdates.beginTime
    from eventdates
    inner join events on events.id = eventdates.eventId
    where eventdates.date >= ? and eventdates.date <= ?
    order by eventdates.date asc";

	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
        $stmt->bind_param("ss", $firstDay, $lastDay);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$dataRows = $result->fetch_all(MYSQLI_ASSOC);
        $result->close();
	}

	if (!$optConnection) $conn->close();
	return $dataRows;
}

function getCalendarEventsInMonth($month, $year, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
    $refDateTime = new DateTime("$year-$month-01");
    $firstDay = $refDateTime->format("Y-m-d");
    $lastDay = $refDateTime->format("Y-m-t");

	$query = "select type, title, date, beginTime
    from calendardates
    where date >= ? and date <= ? and type != 'privatesimpleevent' 
    order by date asc";

	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
        $stmt->bind_param("ss", $firstDay, $lastDay);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$dataRows = $result->fetch_all(MYSQLI_ASSOC);
        $result->close();
	}

	if (!$optConnection) $conn->close();
	return $dataRows;
}

function getCertifiableEventsInDay(string $day, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "select events.id as eventId, events.name as eventName, eventdates.date, eventdates.name, eventdates.beginTime, eventdates.endTime
    from eventdates
    inner join events on events.id = eventdates.eventId
    where eventdates.date = ?
    order by eventdates.beginTime asc";

	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
        $stmt->bind_param("s", $day);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$dataRows = $result->fetch_all(MYSQLI_ASSOC);
        $result->close();
	}

	if (!$optConnection) $conn->close();
	return $dataRows;
}

function getCalendarEventsInDay(string $day, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "select id, type, title, description, date, beginTime, endTime
    from calendardates
    where date = ? and type != 'privatesimpleevent' 
    order by beginTime asc";

	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
        $stmt->bind_param("s", $day);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$dataRows = $result->fetch_all(MYSQLI_ASSOC);
        $result->close();
	}

	if (!$optConnection) $conn->close();
	return $dataRows;
}