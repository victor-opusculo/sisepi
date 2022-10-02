<?php

//Public
require_once("database.php");
require_once("ext.libraryreservations.database.php");

/*
function buildCollectionQuery($baseQuery, $_orderBy, $searchKeywords, $useLimit = true)
{
	$outputInfos = [ "query" => "", "search" => false];
	
	$where = (strlen($searchKeywords) > 3) ? "where " : "";
	
	$whereSearch = "";
	if (strlen($searchKeywords) > 3)
	{
		$whereSearch = "(MATCH (author, title, cdu, cdd, isbn, publisher_edition, provider) AGAINST (?)) ";
		$outputInfos["search"] = true;
		
		//if ($whereCategory || $whereSubcategory) $where .= " and ";
		$where .= $whereSearch;
	}
	
	$orderBy = "";
	switch ($_orderBy)
	{
		case "id": $orderBy = "order by librarycollection.id ASC "; break;
		case "colltype": $orderBy = "order by colltypeenum.value ASC "; break;
		case "title": $orderBy = "order by title ASC "; break;
		case "author": $orderBy = "order by author ASC "; break;
	}
	
	$limit = $useLimit ? "limit ?, ?" : "";
			
	$outputInfos["query"] = $baseQuery . $where . $orderBy . $limit;
	
	return $outputInfos;
}

function getCollectionCount($searchKeywords, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$count = 0;
	
	$baseQuery = "SELECT count(*) FROM librarycollection ";
	$infos = buildCollectionQuery($baseQuery, null, $searchKeywords, false);
	$query = $infos["query"];
	
	if ($stmt = $conn->prepare($query))
	{
		if ($infos["search"])
			$stmt->bind_param("s", $searchKeywords);
		
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		$count = $result->fetch_row()[0];
	}
	
	if (!$optConnection) $conn->close();
	
	return $count;
}

function getCollectionPartially($page, $numResultsOnPage, $__orderBy, $searchKeywords, $optConnection = null)
{
	$_orderBy = ($__orderBy === null || $__orderBy === "") ? "id" : $__orderBy;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	$baseQuery = "SELECT librarycollection.id, colltypeenum.value as collectionTypeName, title, author, cdu, cdd, isbn 
FROM librarycollection 
LEFT JOIN enums colltypeenum ON colltypeenum.type = 'LIBCOLTYPE' and colltypeenum.id = collectionTypeId ";
	
	$infos = buildCollectionQuery($baseQuery, $_orderBy, $searchKeywords, true);
	$query = $infos["query"];
	
	if ($stmt = $conn->prepare($query))
	{
		$calc_page = ($page - 1) * $numResultsOnPage;
		if ($infos["search"])
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
				$dataRows[] = $row;
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getSinglePublication($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	
	$query = "SELECT librarycollection.*, coltypeenum.value as colTypeName, acqtypeenum.value as acqTypeName, periodicityenum.value as periodicityName, 
	if((select count(id) from libraryborrowedpublications where publicationId = ? AND returnDatetime is null) = 0, 1, 0) as isAvailable FROM `librarycollection`
left join enums coltypeenum on coltypeenum.type = 'LIBCOLTYPE' and coltypeenum.id = librarycollection.collectionTypeId
left join enums acqtypeenum on acqtypeenum.type = 'LIBACQTYPE' and acqtypeenum.id = librarycollection.typeAcquisitionId
left join enums periodicityenum on periodicityenum.type = 'LIBPERIOD' and periodicityenum.id = librarycollection.periodicityId 
WHERE librarycollection.id = ?";
	
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("ii", $id, $id);
		$stmt->execute();
		$result = $stmt->get_result();
		if ($result->num_rows > 0)
			$dataRow = $result->fetch_assoc();
		else
			throw new Exception("Registro não localizado.");
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}


function getValidReservationsCount($publicationId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	invalidatePendingAndOldReservations($publicationId, $conn);
	
	$count = 0;
	$query = "SELECT COUNT(*) FROM libraryreservations WHERE publicationId = ? AND borrowedPubId IS NULL AND invalidatedDatetime IS NULL";
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $publicationId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$count = $result->fetch_row()[0];
	}
	
	if (!$optConnection) $conn->close();
	return $count;
}
*/