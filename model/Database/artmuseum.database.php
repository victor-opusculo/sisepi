<?php

require_once("database.php");

function getArtPiecesValueSum($searchKeywords, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$totalValue = 0;
	
	$queryBase = "SELECT SUM(value) FROM artpieces ";
	
	$queryWhere = "";
	if (strlen($searchKeywords) > 3)
		$queryWhere = "WHERE MATCH(name, artist, technique, donor, location, description) against (?) ";
	
	$query = $queryBase . $queryWhere;
	
	if($stmt = $conn->prepare($query))
	{
		if (strlen($searchKeywords) > 3)
			$stmt->bind_param("s", $searchKeywords);

		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		$totalValue = $result->fetch_row()[0];
	}
	
	if (!$optConnection) $conn->close();
	
	return $totalValue;
}

function getArtPiecesCount($searchKeywords, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$totalRecords = 0;
	
	$queryBase = "SELECT COUNT(*) FROM artpieces ";
	
	$queryWhere = "";
	if (strlen($searchKeywords) > 3)
		$queryWhere = "WHERE MATCH(name, artist, technique, donor, location, description) against (?) ";
	
	$query = $queryBase . $queryWhere;
	
	if($stmt = $conn->prepare($query))
	{
		if (strlen($searchKeywords) > 3)
			$stmt->bind_param("s", $searchKeywords);

		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		$totalRecords = $result->fetch_row()[0];
	}
	
	if (!$optConnection) $conn->close();
	
	return $totalRecords;
}

function getArtPiecesPartially($page, $numResultsOnPage, $_orderBy, $searchKeywords, $optConnection = null)
{
	$orderBy = ($_orderBy === null || $_orderBy === "") ? "id" : $_orderBy;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$queryBase = "SELECT id, name, artist, technique, year, mainImageAttachmentFileName FROM artpieces ";
	
	$queryWhere = "";
	if (strlen($searchKeywords) > 3)
		$queryWhere = "WHERE MATCH(name, artist, technique, donor, location, description) against (?) ";
	
	$queryOrderBy = "";
	if ($orderBy === "id")
		$queryOrderBy = "ORDER BY id ASC ";
	else if ($orderBy === "name")
		$queryOrderBy = "ORDER BY name ASC ";
	else if ($orderBy === "artist")
		$queryOrderBy = "ORDER BY artist ASC ";
	else if ($orderBy === "donor")
		$queryOrderBy = "ORDER BY donor ASC ";
	else if ($orderBy === "value")
		$queryOrderBy = "ORDER BY value ASC ";
	else if ($orderBy === "year")
		$queryOrderBy = "ORDER BY year ASC ";
	
	$queryLimit = "LIMIT ?, ?";
	
	$dataRows = null;
	$query = $queryBase . $queryWhere . $queryOrderBy . $queryLimit;

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
				$dataRows[] = $row;
			}
		}
	}
	
	if (!$optConnection) $conn->close();
	return $dataRows;
}

function getSingleArtPiece($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	$query = "SELECT * FROM artpieces WHERE id = ?";
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$dataRow = $result->fetch_assoc();
	}
	
	if (!$optConnection) $conn->close();
	return $dataRow;
}

function getSingleArtPieceIdFromPropertyNumber($cmiPropNumber, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$returnedId = null;
	$query = "SELECT id FROM artpieces WHERE CMI_propertyNumber = ?";
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $cmiPropNumber);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			$returnedId = $result->fetch_row()[0];
	}
	
	if (!$optConnection) $conn->close();
	if ($returnedId === null) throw new Exception("Número de patrimônio de obra de arte não localizado.");
	return $returnedId;
}

function getArtAttachments($artId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	$query = "SELECT * FROM artattachments WHERE artPieceId = ?";
	if($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $artId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
				$dataRows[] = $row;
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getFullArtPiece($id)
{
	$conn = createConnectionAsEditor();
	
	$singleArtPieceDataRow = getSingleArtPiece($id, $conn);
	$artPieceAttachmentsDataRows = getArtAttachments($id, $conn);
	
	$conn->close();
	
	$output = [];
	$output["artPiece"] = $singleArtPieceDataRow;
	$output["artPieceAttachments"] = $artPieceAttachmentsDataRows;
	
	return $output;
}

function getFullArtPieceList($_orderBy, $searchKeywords)
{
	$orderBy = ($_orderBy === null || $_orderBy === "") ? "id" : $_orderBy;
	
	$conn = createConnectionAsEditor();
	
	$queryBase = "SELECT * FROM artpieces ";
	
	$queryWhere = "";
	if (strlen($searchKeywords) > 3)
		$queryWhere = "WHERE MATCH(name, artist, technique, donor, location, description) against (?) ";
	
	$queryOrderBy = "";
	if ($orderBy === "id")
		$queryOrderBy = "ORDER BY id ASC ";
	else if ($orderBy === "name")
		$queryOrderBy = "ORDER BY name ASC ";
	else if ($orderBy === "artist")
		$queryOrderBy = "ORDER BY artist ASC ";
	else if ($orderBy === "donor")
		$queryOrderBy = "ORDER BY donor ASC ";
	else if ($orderBy === "value")
		$queryOrderBy = "ORDER BY value ASC ";
	else if ($orderBy === "year")
		$queryOrderBy = "ORDER BY year ASC ";
	
	$query = $queryBase . $queryWhere . $queryOrderBy;
	$dataRows = null;
	if($stmt = $conn->prepare($query))
	{
		if (strlen($searchKeywords) > 3)
			$stmt->bind_param("s", $searchKeywords);

		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = [];
			while ($row = $result->fetch_assoc())
				$dataRows[] = $row;
		}
	}
	
	$conn->close();
	
	return $dataRows;
}

require_once("artmuseum.uploadFiles.php");

function updateArtPiece($postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$affectedRows = 0;
	
	$query = "update artpieces set CMI_propertyNumber = ?, name = ?, artist = ?, technique = ?, year = ?, size = ?, donor = ?, value = ?, location = ?, description = ?, mainImageAttachmentFileName = ?
	where id = ?";
	if($stmt = $conn->prepare($query))
	{
		$cmiPropNumber = $postData["numCMI_propertyNumber"] ? $postData["numCMI_propertyNumber"] : null;
		$numYear = $postData["numYear"] ? $postData["numYear"] : null;
		$mainImageAttachmentFileName = $postData["radAttachmentMainImage"] ?? null;
		
		$stmt->bind_param("isssissdsssi", 
							$cmiPropNumber,
							$postData["txtPieceName"],
							$postData["txtArtist"],
							$postData["txtTechnique"],
							$numYear,
							$postData["txtSize"],
							$postData["txtDonor"],
							$postData["numValue"],
							$postData["txtLocation"],
							$postData["txtDescription"],
							$mainImageAttachmentFileName,
							$postData["artPieceId"]);
		$execStatus = $stmt->execute();
		if ($execStatus === false)
		{
			$errMessage = $stmt->error;
			$stmt->close();
			if (!$optConnection) $conn->close();
			throw new Exception($errMessage);
		}
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
		
	}
	
	if (!$optConnection) $conn->close();
	
	return $affectedRows;
}

function updateArtAttachments($artPieceId, $postData, $filePostData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$jsonChangesReport = json_decode($postData["attachmentsChangesReport"]);
	$affectedRows = 0;
	
	if ($jsonChangesReport->delete)
		foreach ($jsonChangesReport->delete as $deleteReg)
		{
			$fileNameToDelete = null;
			if($stmt = $conn->prepare("select fileName from artattachments where id = ?"))
			{
				$stmt->bind_param("i", $deleteReg->id);
				$stmt->execute();
				$results = $stmt->get_result();
				$stmt->close();
				
				$row = $results->fetch_assoc();
				$fileNameToDelete = $row["fileName"];
			}
			
			if($stmt = $conn->prepare("delete from artattachments where id = ?"))
			{
				$stmt->bind_param("i", $deleteReg->id);
				$stmt->execute();
				$affectedRows += $stmt->affected_rows;
				$stmt->close();
			}
			
			deleteFile($artPieceId, $fileNameToDelete);
		}
		
	if ($jsonChangesReport->create)
		foreach ($jsonChangesReport->create as $createReg)
		{
			$fileInputElementName = $createReg->fileInputElementName;
			
			$uploadedResult = uploadFile($artPieceId, $filePostData, $fileInputElementName);

			if ($uploadedResult)
				if($stmt = $conn->prepare("insert into artattachments (artPieceId, fileName) values (?, ?)"))
				{
					$fileName = basename($filePostData[$fileInputElementName]["name"]);
					$stmt->bind_param("is", $artPieceId, $fileName);
					$stmt->execute();
					$affectedRows += $stmt->affected_rows;
					$stmt->close();
				}
		}
		
	checkForEmptyDir($artPieceId);
	
	if (!$optConnection) $conn->close();
	
	return $affectedRows;
}

function updateFullArtPiece($postData, $filePostData)
{
	$conn = createConnectionAsEditor();
	$artPieceId = $postData["artPieceId"];
	
	$affectedRows = 0;
	$affectedRows += updateArtPiece($postData, $conn);
	$affectedRows += updateArtAttachments($artPieceId, $postData, $filePostData, $conn);
	
	$conn->close();
	
	return $affectedRows > 0;
}

function createArtPiece($postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$affectedRows = 0;
	$insertedId = null;
	$query = "insert into artpieces (CMI_propertyNumber, name, artist, technique, year, size, donor, value, location, description, mainImageAttachmentFileName) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	if($stmt = $conn->prepare($query))
	{
		
		$cmiPropNumber = $postData["numCMI_propertyNumber"] ? $postData["numCMI_propertyNumber"] : null;
		$numYear = $postData["numYear"] ? $postData["numYear"] : null;
		$mainImageAttachmentFileName = $postData["radAttachmentMainImage"] ?? null;
		
		$stmt->bind_param("isssissdsss", 
							$cmiPropNumber,
							$postData["txtPieceName"],
							$postData["txtArtist"],
							$postData["txtTechnique"],
							$numYear,
							$postData["txtSize"],
							$postData["txtDonor"],
							$postData["numValue"],
							$postData["txtLocation"],
							$postData["txtDescription"],
							$mainImageAttachmentFileName,);
		$execStatus = $stmt->execute();
		if ($execStatus === false)
		{
			$errMessage = $stmt->error;
			$stmt->close();
			if (!$optConnection) $conn->close();
			throw new Exception($errMessage);
		}
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
		$insertedId = $conn->insert_id;
	}
	
	if (!$optConnection) $conn->close();
	
	return [ "newId" => $insertedId, "affectedRows" => $affectedRows ];
}

function createFullArt($postData, $filePostData)
{
	$conn = createConnectionAsEditor();
	
	$affectedRows = 0;
	$newArtPieceReturnArray = createArtPiece($postData, $conn);
	$newId = $newArtPieceReturnArray["newId"];
	$affectedRows += $newArtPieceReturnArray["affectedRows"];
	$affectedRows += updateArtAttachments($newId, $postData, $filePostData, $conn);
	
	$conn->close();
	
	return ["newId" => $newId, "isCreated" => $affectedRows > 0];
}

function deleteFullArt($artPieceId)
{
	$conn = createConnectionAsEditor();
	$affectedRows = 0;
	if($stmt = $conn->prepare("delete from artpieces where id = ?"))
	{
		$stmt->bind_param("i", $artPieceId);
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();
	}
	
	if($stmt = $conn->prepare("delete from artattachments where artPieceId = ?"))
	{
		$stmt->bind_param("i", $artPieceId);
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();
	}
	
	$conn->close();
	
	cleanArtFolder($artPieceId);
	checkForEmptyDir($artPieceId);
	
	return $affectedRows > 0;
}