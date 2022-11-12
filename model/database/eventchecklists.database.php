<?php

require_once("database.php");

function getEventOrEventDateInfos($checklistId, $optConnection = null)
{
	$entityInfos = null;
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$queryEventDate = "SELECT eventdates.id, date, beginTime, endTime, eventdates.name AS eventDateName, eventId, events.name as eventName
	FROM eventdates 
	INNER JOIN events on events.id = eventdates.eventId 
	WHERE eventdates.checklistId = ?";
	$stmt = $conn->prepare($queryEventDate);
	$stmt->bind_param("i", $checklistId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	if ($result->num_rows > 0)
	{
		$entityInfos = $result->fetch_assoc();
		$entityInfos['_entityType'] = 'eventdate';
		
		$result->close();
	}
	else
	{
		$result->close();
		$queryEvent = "SELECT id, name FROM events WHERE checklistId = ?";
		$stmt = $conn->prepare($queryEvent);
		$stmt->bind_param("i", $checklistId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		if ($result->num_rows > 0)
		{
			$entityInfos = $result->fetch_assoc();
			$entityInfos['_entityType'] = 'event';
		}
		$result->close();
	}
	
	if (!$optConnection) $conn->close();
	return $entityInfos;
}

function getEventChecklistTemplatesCount($searchKeywords, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$totalRecords = 0;
	$query = "SELECT count(*) FROM jsontemplates ";
	
	$infos = buildEventChecklistTemplatesQuery($query, $searchKeywords);
	
	$stmt = $conn->prepare($infos['query']);
	if (!empty($infos["bindParamTypes"]))
		$stmt->bind_param($infos['bindParamTypes'], ...$infos['bindParamParamsArray']);	
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	
	$totalRecords = $result->fetch_row()[0];

	if (!$optConnection) $conn->close();
	
	return $totalRecords;
}

function buildEventChecklistTemplatesQuery($baseQuery, $searchKeywords, $useLimit = null)
{
	$outputInfos = [ "query" => "", "bindParamTypes" => "", "bindParamParamsArray" => [] ];
	
	$where = "where type = 'eventchecklist' ";
	
	if (strlen($searchKeywords) > 3)
	{	
		$outputInfos['bindParamTypes'] .= "s";
		$outputInfos['bindParamParamsArray'][] = $searchKeywords;
		$where .= "AND match(name) against (? in boolean mode) ";
	}
	
	$limit = "";
	if ($useLimit !== null)
	{
		$limit = "limit ?, ?";
		$outputInfos["bindParamTypes"] .= "ii";
		$outputInfos["bindParamParamsArray"][] = $useLimit[0];
		$outputInfos["bindParamParamsArray"][] = $useLimit[1];
	}
	
	$orderBy = "ORDER BY name ASC ";
	
	$outputInfos["query"] = $baseQuery . $where . $orderBy . $limit;
		
	return $outputInfos;
}

function getEventChecklistTemplatesPartially($page, $numResultsOnPage, $searchKeywords, $optConnection = null)
{
	$calc_page = ($page - 1) * $numResultsOnPage;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	$query = "SELECT id, name FROM jsontemplates ";
	$infos = buildEventChecklistTemplatesQuery($query, $searchKeywords, [ $calc_page, $numResultsOnPage ]);
	
	$stmt = $conn->prepare($infos['query']);
	if (!empty($infos["bindParamTypes"]))
		$stmt->bind_param($infos['bindParamTypes'], ...$infos['bindParamParamsArray']);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	
	if ($result->num_rows > 0) $dataRows = $result->fetch_all(MYSQLI_ASSOC);
	$result->close();

	if (!$optConnection) $conn->close();
	return $dataRows;
}

function getAllEventChecklistTemplates($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$dataRows = null;
	$query = "SELECT id, name FROM jsontemplates where type = 'eventchecklist'";

	$result = $conn->query($query);
	$dataRows = $result->fetch_all(MYSQLI_ASSOC);

	if (!$optConnection) $conn->close();
	return $dataRows;
}

function getResponsibleUsersList($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$dataRows = null;
	$query = "SELECT id, name FROM users";

	$result = $conn->query($query);
	$dataRows = $result->fetch_all(MYSQLI_ASSOC);

	if (!$optConnection) $conn->close();
	return $dataRows;
}

function getSingleChecklistTemplate($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$dataRow = null;
	$query = "SELECT id, name, templateJson FROM jsontemplates WHERE type = 'eventchecklist' AND id = ?";
	
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	if ($result->num_rows > 0) $dataRow = $result->fetch_assoc();
	$result->close();

	if (!$optConnection) $conn->close();

	return $dataRow;
}

function updateChecklistTemplate($dbEntity, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$affectedRows = 0;
	$dbEntityInfos = $dbEntity->generateSQLUpdateCommandColumnsAndFields();
	$query = "update jsontemplates set $dbEntityInfos[setColumnsAndFields] where $dbEntityInfos[whereCondition]";

	$stmt = $conn->prepare($query);
	$bindParamInfos = $dbEntity->generateBindParamTypesAndValues();
	$stmt->bind_param($bindParamInfos['types'], ...$bindParamInfos['values']);
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();

	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function createChecklistTemplate($dbEntity, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$insertedId = null;
	$affectedRows = 0;

	$dbEntityInfos = $dbEntity->generateSQLCreateCommandColumnsAndFields();
	$query = "insert into jsontemplates ($dbEntityInfos[columns]) values ($dbEntityInfos[fields])";

	$stmt = $conn->prepare($query);
	$bindParamInfos = $dbEntity->generateBindParamTypesAndValues();
	$stmt->bind_param($bindParamInfos['types'], ...$bindParamInfos['values']); 
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();
		
	$insertedId = $conn->insert_id;

	if (!$optConnection) $conn->close();
	return [ 'isCreated' => $affectedRows > 0, 'newId' => $insertedId ];
}

function deleteChecklistTemplate($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$affectedRows = 0;
	$query = "delete from jsontemplates where type = 'eventchecklist' and id = ?";

	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $id); 
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();

	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function getSingleChecklist($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$dataRow = null;
	$query = "SELECT * from eventchecklists where id = ?";

	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $id); 
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$dataRow = $result->fetch_assoc();
	$result->close();

	if (!$optConnection) $conn->close();
	return $dataRow;
}

function updateSingleChecklist($dbEntity, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$dbEntityInfos = $dbEntity->generateSQLUpdateCommandColumnsAndFields();
	$query = "UPDATE eventchecklists SET $dbEntityInfos[setColumnsAndFields] where $dbEntityInfos[whereCondition]";
	$stmt = $conn->prepare($query);
	$bindParamInfos = $dbEntity->generateBindParamTypesAndValues();
	$stmt->bind_param($bindParamInfos['types'], ...$bindParamInfos['values']); 
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();

	verifyIfChecklistIsFinalized($dbEntity->id, $conn);

	if (!$optConnection) $conn->close();
	return $affectedRows;
}

function deleteSingleChecklist($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "DELETE from eventchecklists where id = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $id); 
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();

	if (!$optConnection) $conn->close();
	return $affectedRows;
}

function deleteMultipleChecklists(array $ids, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$fieldsStrings = array_map( fn($id) => '?', $ids);

	$query = "DELETE from eventchecklists where id in (" . implode(',', $fieldsStrings) . ")";
	$stmt = $conn->prepare($query);
	$stmt->bind_param(str_repeat('i', count($fieldsStrings)), ...$ids); 
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();

	if (!$optConnection) $conn->close();
	return $affectedRows;
}

function setChecklistActionOnEvent($eventId, $actionOrNewFromTemplateId, $optConnection = null)
{
	function deleteCurrentChecklists($eventId, $conn)
	{
		$affectedRows = 0;
		$query1 = "DELETE from eventchecklists where eventchecklists.id = (Select checklistId from events where events.id = ?)";
		$stmt = $conn->prepare($query1);
		$stmt->bind_param("i", $eventId); 
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();

		$query3 = "UPDATE events SET checklistId = NULL where id = ?";
		$stmt = $conn->prepare($query3);
		$stmt->bind_param("i", $eventId); 
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();

		return $affectedRows;
	}

	function getPrePostEventChecklistJson($templateId, $conn)
	{
		$querySelectPrePostEventChecklsitJson = "SELECT json_object(
			'preevent', json_extract(templateJson, '$.preevent'), 
			'postevent', json_extract(templateJson, '$.postevent')) 
			from jsontemplates where type = 'eventchecklist' and id = ?";
		$stmt = $conn->prepare($querySelectPrePostEventChecklsitJson);
		$stmt->bind_param("i", $templateId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$prePostEventChecklistJson = $result->fetch_row()[0];
		$result->close(); 
		return $prePostEventChecklistJson;
	}

	function setEventsChecklistId($eventId, $checklistId, $conn)
	{
		$query = "UPDATE events SET checklistId = ? WHERE id = ?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("ii", $checklistId, $eventId);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
		return $affectedRows;
	}

	function insertEventChecklist($eventId, $templateId, $conn)
	{
		$json = getPrePostEventChecklistJson($templateId, $conn);
		$queryInsertEventChecklist = "INSERT into eventchecklists (finalized, checklistJson) values (0, ?)";
		$stmt = $conn->prepare($queryInsertEventChecklist);
		$stmt->bind_param("s", $json);
		$stmt->execute();
		$newId = $conn->insert_id;
		$stmt->close();

		$affectedRows = 0;
		if ($newId)
		{
			$affectedRows++;
			$affectedRows += setEventsChecklistId($eventId, $newId, $conn);
		}
		
		return $affectedRows;
	}

	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$affectedRows = 0;
	if ((int)$actionOrNewFromTemplateId === 0)
	{
		$affectedRows = 0;
	}
	else if ((int)$actionOrNewFromTemplateId === -1)
	{
		$affectedRows += deleteCurrentChecklists($eventId, $conn);
	}
	else if ((int)$actionOrNewFromTemplateId > 0)
	{
		$affectedRows += deleteCurrentChecklists($eventId, $conn);
		$affectedRows += insertEventChecklist($eventId, $actionOrNewFromTemplateId, $conn);
	}

	if (!$optConnection) $conn->close();
	return $affectedRows;
}

function getEventDateChecklistJson($templateId, $conn)
{
	$querySelectEventDateChecklsitJson = "SELECT json_object(
		'eventdate', json_extract(templateJson, '$.eventdate')) 
		from jsontemplates where type = 'eventchecklist' and id = ?";
	$stmt = $conn->prepare($querySelectEventDateChecklsitJson);
	$stmt->bind_param("i", $templateId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$eventDateChecklistJson = $result->fetch_row()[0];
	$result->close(); 
	return $eventDateChecklistJson;
}

function setChecklistActionOnEventDates($checklistActionsOnEventDates, $optConnection = null)
{
	function deleteCurrentChecklist($eventDateId, $conn)
	{
		$affectedRows = 0;
		$query1 = "DELETE from eventchecklists where eventchecklists.id = (Select checklistId from eventdates where id = ?)";
		$stmt = $conn->prepare($query1);
		$stmt->bind_param("i", $eventDateId);
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();

		$query2 = "UPDATE eventdates SET checklistId = NULL where id = ?";
		$stmt = $conn->prepare($query2);
		$stmt->bind_param("i", $eventDateId); 
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();

		return $affectedRows;
	}

	function setEventDateChecklistId($eventDateId, $checklistId, $conn)
	{
		$query = "UPDATE eventdates SET checklistId = ? WHERE id = ?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("ii", $checklistId, $eventDateId);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
		return $affectedRows;
	}

	function insertEventDateChecklists($eventDateId, $templateId, $conn)
	{
		$json = getEventDateChecklistJson($templateId, $conn);
		$queryInsertEventDateChecklist = "INSERT into eventchecklists (finalized, checklistJson) values (0, ?)";
		$affectedRows = 0;
		$stmt = $conn->prepare($queryInsertEventDateChecklist);
		$stmt->bind_param("s", $json);
		$stmt->execute();
		$newId = $conn->insert_id;
		$stmt->close();
		
		if ($newId)
		{
			$affectedRows++;
			$affectedRows += setEventDateChecklistId($eventDateId, $newId, $conn);
		}
			
		return $affectedRows;
	}

	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$affectedRows = 0;

	foreach ($checklistActionsOnEventDates as $eventDateId => $actionOrNewFromTemplateId)
	{
		if ((int)$actionOrNewFromTemplateId === 0)
		{
			$affectedRows += 0;
		}
		else if ((int)$actionOrNewFromTemplateId === -1)
		{
			$affectedRows += deleteCurrentChecklist($eventDateId, $conn);
		}
		else if ((int)$actionOrNewFromTemplateId > 0)
		{
			$affectedRows += deleteCurrentChecklist($eventDateId, $conn);
			$affectedRows += insertEventDateChecklists($eventDateId, $actionOrNewFromTemplateId, $conn);
		}
	}

	if (!$optConnection) $conn->close();
	return $affectedRows;
}

function setChecklistItemValue($checklistId, $itemPath, $value, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "UPDATE eventchecklists SET checklistJson = JSON_SET(checklistJson, ?, ?) WHERE id = ?";
	$stmt = $conn->prepare($query);
	$itemPathFull = $itemPath . ".value";
	$stmt->bind_param("ssi", $itemPathFull, $value, $checklistId);
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();

	$returnArray = [ 'affectedRows' => $affectedRows, 'isChecklistCompleted' => verifyIfChecklistIsFinalized($checklistId, $conn) ];
	if (!$optConnection) $conn->close();
	return $returnArray;
}

function verifyIfChecklistIsFinalized($checklistId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$itemsFullNumber = "select COALESCE(json_length(json_extract(checklistJson, '$.*[*].title', '$.*[*].checkList[*].name')), 0) FROM `eventchecklists` WHERE id = ?";
	$subChecklistNumber = "select COALESCE(json_length(json_extract(checklistJson, '$.*[*].checkList')), 0) FROM `eventchecklists` WHERE id = ?";
	$queryNumberOfCheckableItems = "select ($itemsFullNumber) - ($subChecklistNumber)";

	$stmt = $conn->prepare($queryNumberOfCheckableItems);
	$stmt->bind_param("ii", $checklistId, $checklistId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	$numberOfCheckableItems = $result->fetch_row()[0];
	$result->close();

	$queryItemsSetValues = "select json_extract(checklistJson, '$.*[*].value', '$.*[*].checkList[*].value') FROM `eventchecklists` WHERE id = ?";
	$stmt = $conn->prepare($queryItemsSetValues);
	$stmt->bind_param("i", $checklistId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	$valuesArray = json_decode($result->fetch_row()[0]);
	$result->close();
	
	$checkedValues = isset($valuesArray) ? array_filter($valuesArray, fn($val) => $val !== '0' && $val !== '') : 0;
	
	setChecklistFinalizedState($checklistId, count($checkedValues) === $numberOfCheckableItems, $conn);

	if (!$optConnection) $conn->close();
	return count($checkedValues) === $numberOfCheckableItems;
}

function setChecklistFinalizedState($checklistId, bool $finalized, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = $finalized ? "UPDATE eventchecklists SET finalized = 1 WHERE id = ?" : "UPDATE eventchecklists SET finalized = 0 WHERE id = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $checklistId);
	$stmt->execute();
	$stmt->close();

	if (!$optConnection) $conn->close();
}