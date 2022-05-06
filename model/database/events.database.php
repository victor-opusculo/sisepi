<?php
//Private
require_once("database.php");

require_once("events.uploadFiles.php");

//Get single event with type name
function getSingleEvent($id , $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$row = null;
	if($stmt = $conn->prepare("select events.*, enums.value as 'typeName' 
	from events 
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

function getEventsPartially($page, $numResultsOnPage, $_orderBy, $searchKeywords, $optConnection = null)
{
	$orderBy = ($_orderBy === null || $_orderBy === "") ? "date" : $_orderBy;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	
	$queryBase = "SELECT events.id, events.name,  MIN(eventdates.date) AS date, enums.value as 'typeName' 
		FROM `events` 
		INNER JOIN eventdates ON eventdates.eventId = events.id 
		inner JOIN enums on enums.type = 'EVENT' and enums.id = events.typeId ";
	$querySearch = "";
	$queryGroupBy = "GROUP BY events.id, events.name ";
	$queryOrderBy = "";
	
	if (strlen($searchKeywords) > 3)
		$querySearch = "where match (events.name, events.moreInfos) against (?) ";
	
	if ($orderBy === "date")
		$queryOrderBy = "order by eventdates.date desc limit ?, ?";
	else
		$queryOrderBy = "order by events.name asc limit ?, ?";
	
	$query = $queryBase . $querySearch . $queryGroupBy . $queryOrderBy;
	if($stmt = $conn->prepare($query))
	{
		$calc_page = ($page - 1) * $numResultsOnPage;
		if ($querySearch !== "")
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
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getEventsCount($searchKeywords, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
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
	
	if (!$optConnection) $conn->close();
	
	return $totalRecords;
}

function getEventWorkPlan($eventId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$row = null;
	if($stmt = $conn->prepare("select * from eventworkplans where eventId = ?"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$row = $result->fetch_assoc();
	}
	
	if (!$optConnection) $conn->close();
	
	return $row;
}

//Get event dates with professor name
function getEventDates($eventId , $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	
	if($stmt = $conn->prepare("SELECT eventdates.* , aes_decrypt(professors.name,'$__cryptoKey') as 'professorName' FROM eventdates LEFT JOIN professors ON (eventdates.professorId = professors.id) WHERE eventdates.eventId = ? ORDER BY eventdates.date ASC"))
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

function getEventTypes($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	if($stmt = $conn->prepare("select id, value as name from enums where type = 'EVENT'"))
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

function getEventTypeName($typeId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$row = null;
	if($stmt = $conn->prepare("select value from enums where type = 'EVENT' and id = ?"))
	{
		$stmt->bind_param("i", $typeId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
		}
	}
	
	if (!$optConnection) $conn->close();
	
	if ($row !== null)
		return $row["value"];
	else
		return null;
}

function getProfessorName($profId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$row = null;
	if($stmt = $conn->prepare("select aes_decrypt(name, '$__cryptoKey') as name from professors where id = ?"))
	{
		$stmt->bind_param("i", $profId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
		}
	}
	
	if (!$optConnection) $conn->close();
	
	if ($row !== null)
		return $row["name"];
	else
		return null;

}

function getProfessors($optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	if($stmt = $conn->prepare("select id, aes_decrypt(name, '$__cryptoKey') as name from professors order by name"))
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

function getFullEvent($id)
{
	$conn = createConnectionAsEditor();
	
	$singleEventDataRows = getSingleEvent($id, $conn);
	$eventWorkPlan = getEventWorkPlan($id, $conn);
	$eventdatesDataRows = getEventDates($id, $conn);
	$eventattachmentsDataRows = getEventAttachments($id, $conn);
	
	$conn->close();
	
	
	$output = [];
	$output["event"] = $singleEventDataRows;
	$output["eventworkplan"] = $eventWorkPlan;
	$output["eventdates"] = $eventdatesDataRows;
	$output["eventattachments"] = $eventattachmentsDataRows;
	
	return $output;
}

function createEvent($dbEntity, $postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$insertedId = null;
	$affectedRows = 0;

	$dbEntityInfos = $dbEntity->generateSQLCreateCommandColumnsAndFields();
	$query = "insert into events ($dbEntityInfos[columns]) values ($dbEntityInfos[fields])";

	if($stmt = $conn->prepare($query))
	{
		$bindParamInfos = $dbEntity->generateBindParamTypesAndValues();
		$stmt->bind_param($bindParamInfos['types'], ...$bindParamInfos['values']); 
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
		
		$insertedId = $conn->insert_id;
	}
	
	if (!$optConnection) $conn->close();
	
	return [ "newId" => $insertedId, "affectedRows" => $affectedRows ];
}

function updateEvent($dbEntity, $postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$affectedRows = 0;
	$dbEntityInfos = $dbEntity->generateSQLUpdateCommandColumnsAndFields();
	$query = "update events set $dbEntityInfos[setColumnsAndFields] where $dbEntityInfos[whereCondition]";

	if($stmt = $conn->prepare($query))
	{
		$bindParamInfos = $dbEntity->generateBindParamTypesAndValues();
		$stmt->bind_param($bindParamInfos['types'], ...$bindParamInfos['values']);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $affectedRows;
}

function updateEventDates($eventId, $postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$jsonChangesReport = json_decode($postData["eventdates:eventDatesChangesReport"]);
	
	$affectedRows = 0;
	
	//Update existing event dates
	if ($jsonChangesReport->update)
		foreach ($jsonChangesReport->update as $updateReg)
		{
			if($stmt = $conn->prepare("update eventdates set date = ?, beginTime = ?, endTime = ?, name = ?, professorId = ?, presenceListNeeded = ?, presenceListPassword = ? where id = ?"))
			{
				$stmt->bind_param("ssssiisi", $updateReg->date, $updateReg->beginTime, $updateReg->endTime, $updateReg->name, $updateReg->professorId, $updateReg->presenceListEnabled, $updateReg->presenceListPassword, $updateReg->id);
				$stmt->execute();
				$affectedRows += $stmt->affected_rows;
				$stmt->close();
			}
		}
	
	//Create event dates
	if ($jsonChangesReport->create)
		foreach ($jsonChangesReport->create as $createReg)
		{
			if($stmt = $conn->prepare("insert into eventdates (date, beginTime, endTime, name, professorId, presenceListNeeded, presenceListPassword, eventId) values (?, ?, ?, ?, ?, ?, ?, ?)"))
			{
				$stmt->bind_param("ssssiisi", $createReg->date, $createReg->beginTime, $createReg->endTime, $createReg->name, $createReg->professorId, $createReg->presenceListEnabled, $createReg->presenceListPassword, $eventId);
				$stmt->execute();
				$affectedRows += $stmt->affected_rows;
				$stmt->close();
			}
		}
	
	//Delete event dates
	if ($jsonChangesReport->delete)
	{
		foreach ($jsonChangesReport->delete as $deleteReg)
		{
			if($stmt = $conn->prepare("delete from eventdates where id = ?"))
			{
				$stmt->bind_param("i", $deleteReg->id);
				$stmt->execute();
				$affectedRows += $stmt->affected_rows;
				$stmt->close();
			}
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $affectedRows;
}

function updateEventAttachments($eventId, $postData, $filePostData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$jsonChangesReport = json_decode($postData["eventattachments:eventAttachmentsChangesReport"]);
	
	$affectedRows = 0;
	
	//Delete event attachment
	if ($jsonChangesReport->delete)
		foreach ($jsonChangesReport->delete as $deleteReg)
		{
			$fileNameToDelete = null;
			if($stmt = $conn->prepare("select fileName from eventattachments where id = ?"))
			{
				$stmt->bind_param("i", $deleteReg->id);
				$stmt->execute();
				$results = $stmt->get_result();
				$stmt->close();
				
				$row = $results->fetch_assoc();
				$fileNameToDelete = $row["fileName"];
			}
			
			if($stmt = $conn->prepare("delete from eventattachments where id = ?"))
			{
				$stmt->bind_param("i", $deleteReg->id);
				$stmt->execute();
				$affectedRows += $stmt->affected_rows;
				$stmt->close();
			}
			
			deleteFile($eventId, $fileNameToDelete);
		}
	
	//Create event attachment
	if ($jsonChangesReport->create)
		foreach ($jsonChangesReport->create as $createReg)
		{
			$fileInputElementName = $createReg->fileInputElementName;
			$uploadResult = uploadFile($eventId, $filePostData, $fileInputElementName);
			
			if ($uploadResult)
				if($stmt = $conn->prepare("insert into eventattachments (eventId, fileName) values (?, ?)"))
				{
					$fileName = basename($filePostData[$fileInputElementName]["name"]);
					$stmt->bind_param("is", $eventId, $fileName);
					$stmt->execute();
					$affectedRows += $stmt->affected_rows;
					$stmt->close();
				}
			
		}
		
	checkForEmptyDir($eventId);
	
	if (!$optConnection) $conn->close();
	
	return $affectedRows;
}

function updateWorkPlan($dbEntity, $newEventId = null, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "";
	$affectedRows = 0;
	if (empty($dbEntity->eventId))
	{
		$dbEntity->eventId = $newEventId;
		$dbEntityInfos = $dbEntity->generateSQLCreateCommandColumnsAndFields();
		$query = "INSERT into eventworkplans ($dbEntityInfos[columns]) values ($dbEntityInfos[fields])";
	}
	else if (empty($dbEntity->id) && empty($newEventId))
	{
		$dbEntityInfos = $dbEntity->generateSQLCreateCommandColumnsAndFields();
		$query = "INSERT into eventworkplans ($dbEntityInfos[columns]) values ($dbEntityInfos[fields])";
	}
	else if (!empty($dbEntity->id))
	{
		$dbEntityInfos = $dbEntity->generateSQLUpdateCommandColumnsAndFields();
		$query = "UPDATE eventworkplans SET $dbEntityInfos[setColumnsAndFields] where $dbEntityInfos[whereCondition]";
	}

	if ($stmt = $conn->prepare($query))
	{
		$bindParamInfos = $dbEntity->generateBindParamTypesAndValues();
		$stmt->bind_param($bindParamInfos['types'], ...$bindParamInfos['values']);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	if (!$optConnection) $conn->close();
	return $affectedRows;
}

function createFullEvent($dbEntities, $postData, $filePostData)
{
	$conn = createConnectionAsEditor();
		
	$affectedRows = 0;
	
	$newEventReturnArray = createEvent($dbEntities['main'], $postData, $conn);
	$newId = $newEventReturnArray["newId"];
	$affectedRows += $newEventReturnArray["affectedRows"];
	$affectedRows += updateWorkPlan($dbEntities['workPlan'], $newId, $conn);
	$affectedRows += updateEventDates($newId, $postData, $conn);
	$affectedRows += updateEventAttachments($newId, $postData, $filePostData, $conn);
	
	$conn->close();
	
	return [ "newId" => $newId, "isCreated" => $affectedRows > 0];
}

function updateFullEvent($dbEntities, $postData, $filePostData)
{
		$conn = createConnectionAsEditor();
		
		$eventId = $dbEntities['main']->id;
		
		$affectedRows = 0;
		$affectedRows += updateEvent($dbEntities['main'], $postData, $conn);
		$affectedRows += updateWorkPlan($dbEntities['workPlan'], null, $conn);
		$affectedRows += updateEventDates($eventId, $postData, $conn);
		$affectedRows += updateEventAttachments($eventId, $postData, $filePostData, $conn);
		
		$conn->close();
		
		return $affectedRows > 0;
}

function deleteFullEvent($eventId)
{
	$conn = createConnectionAsEditor();
	$affectedRows = 0;
	if($stmt = $conn->prepare("delete from events where id = ?"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();
	}

	if($stmt = $conn->prepare("delete from eventworkplans where eventId = ?"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();
	}
	
	if($stmt = $conn->prepare("delete from eventdates where eventId = ?"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();
	}
	
	if($stmt = $conn->prepare("delete from eventattachments where eventId = ?"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();
	}
	
	if($stmt = $conn->prepare("delete from subscriptionstudents where eventId = ?"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();
	}
	
	if($stmt = $conn->prepare("delete from presencerecords where eventId = ?"))
	{
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();
	}
	
	$conn->close();
	
	cleanEventFolder($eventId);
	checkForEmptyDir($eventId);
	
	return $affectedRows > 0;
}