<?php
//Public
require_once("database.php");

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