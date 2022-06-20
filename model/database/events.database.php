<?php
//Private
require_once("database.php");
require_once("eventchecklists.database.php");
require_once("events.uploadFiles.php");

//Get single event with type name
function getSingleEvent($id , $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$row = null;
	if($stmt = $conn->prepare("select events.*, enums.value as 'typeName', jsontemplates.name as 'surveyTemplateName',
	(select group_concat(COALESCE(eventlocations.type, 'null')) from eventdates left join eventlocations on eventlocations.id = eventdates.locationId where eventdates.eventId = events.id) as locTypes 
	from events 
	left join jsontemplates on jsontemplates.type = 'eventsurvey' and jsontemplates.id = events.surveyTemplateId 
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
	
	$queryBase = "SELECT events.id, events.name,  MIN(eventdates.date) AS date, enums.value as 'typeName', 
	(select group_concat(COALESCE(eventlocations.type, 'null')) from eventdates left join eventlocations on eventlocations.id = eventdates.locationId where eventdates.eventId = events.id) as locTypes
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

//Get event dates
function getEventDates($eventId , $optConnection = null)
{	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	
	if($stmt = $conn->prepare("SELECT eventdates.*
	FROM eventdates 
	LEFT JOIN eventlocations ON eventlocations.id = eventdates.locationId 
	WHERE eventdates.eventId = ? 
	ORDER BY eventdates.date ASC"))
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

function getEventDatesProfessors($eventDateId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$dataRows = null;
	$query = "SELECT eventdatesprofessors.eventDateId, eventdatesprofessors.professorId, aes_decrypt(professors.name, '$__cryptoKey') as professorName from eventdatesprofessors
	left join professors on professors.id = eventdatesprofessors.professorId
	where eventDateId = ?";

	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $eventDateId);
	$stmt->execute();
	$result = $stmt->get_result();
	$dataRows = $result->fetch_all(MYSQLI_ASSOC);
	$stmt->close();
	$result->close();

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
			$row = $result->fetch_assoc();
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
			$row = $result->fetch_assoc();
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
			$dataRows = $result->fetch_all(MYSQLI_ASSOC);
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

	$eventdatesprofessorsDataRows = [];
	foreach ($eventdatesDataRows as $eddr)
		$eventdatesprofessorsDataRows[$eddr['id']] = getEventDatesProfessors($eddr['id'], $conn);
	
	$conn->close();
	
	
	$output = [];
	$output["event"] = $singleEventDataRows;
	$output["eventworkplan"] = $eventWorkPlan;
	$output["eventdates"] = $eventdatesDataRows;
	$output["eventdatesprofessors"] = $eventdatesprofessorsDataRows;
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
	$checklistActionsOnEventDates = [];
	
	//Update existing event dates
	if ($jsonChangesReport->update)
		foreach ($jsonChangesReport->update as $updateReg)
		{
			if($stmt = $conn->prepare("update eventdates set date = ?, beginTime = ?, endTime = ?, name = ?, presenceListNeeded = ?, presenceListPassword = ?, locationId = ?, locationInfosJson = ? where id = ?"))
			{
				$stmt->bind_param("ssssisisi", $updateReg->date, $updateReg->beginTime, $updateReg->endTime, $updateReg->name, $updateReg->presenceListEnabled, $updateReg->presenceListPassword, $updateReg->locationId, $updateReg->locationInfosJson, $updateReg->id);
				$stmt->execute();
				$affectedRows += $stmt->affected_rows;
				$stmt->close();

				$checklistActionsOnEventDates[$updateReg->id] = $updateReg->checklistAction;

				$affectedRows += updateEventDatesProfessors($updateReg->id, $updateReg->professors, $conn);
			}
		}
	
	//Create event dates
	if ($jsonChangesReport->create)
		foreach ($jsonChangesReport->create as $createReg)
		{
			if($stmt = $conn->prepare("insert into eventdates (date, beginTime, endTime, name, presenceListNeeded, presenceListPassword, locationId, locationInfosJson, eventId) values (?, ?, ?, ?, ?, ?, ?, ?, ?)"))
			{
				$stmt->bind_param("ssssisisi", $createReg->date, $createReg->beginTime, $createReg->endTime, $createReg->name, $createReg->presenceListEnabled, $createReg->presenceListPassword, $createReg->locationId, $createReg->locationInfosJson, $eventId);
				$stmt->execute();
				$affectedRows += $stmt->affected_rows;
				$newId = (int)$conn->insert_id;
				$stmt->close();

				$checklistActionsOnEventDates[$newId] = $createReg->checklistAction;

				$affectedRows += updateEventDatesProfessors($newId, $createReg->professors, $conn);
			}
		}
	
	//Delete event dates
	if ($jsonChangesReport->delete)
	{
		foreach ($jsonChangesReport->delete as $deleteReg)
		{
			//Delete checklist associated with this event date
			if($stmt = $conn->prepare("select checklistId from eventdates where id = ?"))
			{
				$stmt->bind_param("i", $deleteReg->id);
				$stmt->execute();
				$result = $stmt->get_result();
				$stmt->close();
				$checklistId = $result->fetch_row()[0];
				$result->close();
				$affectedRows += !empty($checklistId) ? deleteSingleChecklist($checklistId, $conn) : 0;
			}

			//Delete event date professors
			if($stmt = $conn->prepare("delete from eventdatesprofessors where eventDateId = ?"))
			{
				$stmt->bind_param("i", $deleteReg->id);
				$stmt->execute();
				$affectedRows += $stmt->affected_rows;
				$stmt->close();
			}

			//Delete event date
			if($stmt = $conn->prepare("delete from eventdates where id = ?"))
			{
				$stmt->bind_param("i", $deleteReg->id);
				$stmt->execute();
				$affectedRows += $stmt->affected_rows;
				$stmt->close();
			}
		}
	}

	$affectedRows += setChecklistActionOnEventDates($checklistActionsOnEventDates, $conn);
	
	if (!$optConnection) $conn->close();

	return $affectedRows;
}

function updateEventDatesProfessors($eventDateId, $professorIdsArray, $optConnection = null)
{
	$existentProfessors = [];
	$professorIdsArrayUnique = array_unique($professorIdsArray);
	$affectedRows = 0;

	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$querySelectExistent = "SELECT professorId from eventdatesprofessors where eventDateId = ?";
	$stmt = $conn->prepare($querySelectExistent);
	$stmt->bind_param("i", $eventDateId);
	$stmt->execute();
	$resultExistent = $stmt->get_result();
	$stmt->close();
	while ($row = $resultExistent->fetch_assoc())
		$existentProfessors[] = $row['professorId'];
	$resultExistent->close();

	foreach ($professorIdsArrayUnique as $updatedId)
	{
		if (array_search($updatedId, $existentProfessors) === false)
		{
			$queryInsertProfessor = "INSERT into eventdatesprofessors (eventDateId, professorId) values (?, ?)";
			$stmt = $conn->prepare($queryInsertProfessor);
			$stmt->bind_param("ii", $eventDateId, $updatedId);
			$stmt->execute();
			$affectedRows += $stmt->affected_rows;
			$stmt->close();
		}
	}

	foreach ($existentProfessors as $existentId)
	{
		if (array_search($existentId, $professorIdsArrayUnique) === false)
		{
			$queryDeleteProfessor = "DELETE from eventdatesprofessors where eventDateId = ? AND professorId = ?";
			$stmt = $conn->prepare($queryDeleteProfessor);
			$stmt->bind_param("ii", $eventDateId, $existentId);
			$stmt->execute();
			$affectedRows += $stmt->affected_rows;
			$stmt->close();
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

	if(setChecklistActionOnEvent($newId, $postData['selEventChecklistActions'], $conn))
		$affectedRows += 1;
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
		$affectedRows += setChecklistActionOnEvent($eventId, $postData['selEventChecklistActions'], $conn);
		$affectedRows += updateWorkPlan($dbEntities['workPlan'], null, $conn);
		$affectedRows += updateEventDates($eventId, $postData, $conn);
		$affectedRows += updateEventAttachments($eventId, $postData, $filePostData, $conn);
		
		if ($postData['selEventChecklistActions'] == 0)
			$affectedRows += updateSingleChecklist($dbEntities['checklist'], $conn);

		$conn->close();
		
		return $affectedRows > 0;
}

function deleteFullEvent($eventId)
{
	$conn = createConnectionAsEditor();
	$affectedRows = 0;

	function deleteChecklists($eventId, $conn)
	{
		$checklistIds = [];
		if($stmt = $conn->prepare("select checklistId from events where id = ?"))
		{
			$stmt->bind_param("i", $eventId);
			$stmt->execute();
			$eventChecklistId = $stmt->get_result()->fetch_row()[0];
			if ($eventChecklistId)
				$checklistIds[] = $eventChecklistId;
			$stmt->close();
		}
		if($stmt = $conn->prepare("select checklistId from eventdates where eventId = ?"))
		{
			$stmt->bind_param("i", $eventId);
			$stmt->execute();
			$result = $stmt->get_result();
			while ($row = $result->fetch_row())
				$checklistIds[] = $row[0];
			$result->close();
			$stmt->close();
		}

		return deleteMultipleChecklists($checklistIds, $conn);
	}

	function deleteEventDatesProfessors($eventId, $conn)
	{
		$eventDatesIds = [];
		$affectedRows = 0;
		$stmt = $conn->prepare("SELECT id from eventDates Where eventId = ?");
		$stmt->bind_param("i", $eventId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		while ($row = $result->fetch_assoc())
			$eventDatesIds[] = $row['id'];
		$result->close();

		foreach ($eventDateIds as $edId)
			$affectedRows += updateEventDatesProfessors($edId, [], $conn);

		return $affectedRows;
	}

	$affectedRows += deleteChecklists($eventId, $conn);
	$affectedRows += deleteEventDatesProfessors($eventId, $conn);

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

	if($stmt = $conn->prepare("delete from eventsurveys where eventId = ?"))
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