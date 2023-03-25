<?php
require_once "database.php";

function getEventSurveysReport(array $eventIdList, ?mysqli $optConnection = null)
{
    if (count($eventIdList) < 1) return null;
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $eventIdsString = implode(",", array_map( fn($id) => $conn->real_escape_string($id), $eventIdList));
    $query = "SELECT eventsurveys.*, events.name as eventName FROM `eventsurveys` 
    LEFT JOIN events ON events.id = eventsurveys.eventId
    WHERE eventId IN ($eventIdsString) ";
    $result = $conn->query($query);
    $dataRows = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRows;
}

function getEventsArrayBasicInfos(array $ids , ?mysqli $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
    $fieldsQuery = str_repeat('?,', count($ids) - 1) . '?';
    $fieldsDataTypes = str_repeat('i', count($ids));

	$stmt = $conn->prepare("SELECT events.*, enums.value as 'typeName', min(eventdates.date) as minDate, max(eventdates.date) as maxDate,
	(select group_concat(COALESCE(eventlocations.type, 'null')) from eventdates left join eventlocations on eventlocations.id = eventdates.locationId where eventdates.eventId = events.id) as locTypes 
	from events 
	left join eventdates ON eventdates.eventId = events.id
	right join enums on enums.type = 'EVENT' and enums.id = events.typeId 
	where events.id IN ($fieldsQuery)
	group by events.id ");
	$stmt->bind_param($fieldsDataTypes, ...$ids);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$rows = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : null;
	if (!$optConnection) $conn->close();
	return $rows;
}