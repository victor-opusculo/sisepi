<?php
//private
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
    where date >= ? and date <= ? 
    order by date asc, beginTime asc";

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
    where date = ? 
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

function getSingleCalendarEvent($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = "select id, type, title, description, date, beginTime, endTime
    from calendardates
    where id = ?";

	$dataRow = null;
	if($stmt = $conn->prepare($query))
	{
        $stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$dataRow = $result->fetch_assoc();
        $result->close();
	}

	if (!$optConnection) $conn->close();
	return $dataRow;
}

function createCalendarEvent($dbEntity, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dbEntityInfos = $dbEntity->generateSQLCreateCommandColumnsAndFields();

	$query = "insert into calendardates ($dbEntityInfos[columns]) values ($dbEntityInfos[fields])";

	$affectedRows = 0;
	$newId = null;
	if($stmt = $conn->prepare($query))
	{
		$bindParamsInfos = $dbEntity->generateBindParamTypesAndValues();
        $stmt->bind_param($bindParamsInfos['types'], ...$bindParamsInfos['values']);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
		$newId = $conn->insert_id;
	}

	if (!$optConnection) $conn->close();
	return [ 'newId' => $newId, 'isCreated' => $affectedRows > 0 ];
}

function editCalendarEvent($dbEntity, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dbEntityInfos = $dbEntity->generateSQLUpdateCommandColumnsAndFields();

	$query = "update calendardates set $dbEntityInfos[setColumnsAndFields] where $dbEntityInfos[whereCondition]";

	$affectedRows = 0;
	if($stmt = $conn->prepare($query))
	{
		$bindParamsInfos = $dbEntity->generateBindParamTypesAndValues();
        $stmt->bind_param($bindParamsInfos['types'], ...$bindParamsInfos['values']);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}

	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function deleteCalendarEvent($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "delete from calendardates where id = ?";
	$affectedRows = 0;
	if($stmt = $conn->prepare($query))
	{
        $stmt->bind_param("i", $id);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}

	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}