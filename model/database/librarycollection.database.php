<?php
require_once("database.php");


/* function getCategories($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = [];
	$result = $conn->query("select * from libraryCategories where parentId = 0 order by name");
	
	if ($result->num_rows > 0)
		while ($row = $result->fetch_assoc())
			$dataRows[] = $row;
			
	if (!$optConnection) $conn->close();
	
	return $dataRows;
} */

/* function getSubcategories($categoryId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = [];
	if ($stmt = $conn->prepare("select * from libraryCategories where parentId = ? order by name"))
	{
		$stmt->bind_param("i", $categoryId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
			while ($row = $result->fetch_assoc())
				$dataRows[] = $row;
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
} */

function getCollectionTypes($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = [];
	$result = $conn->query("select * from enums where type = 'LIBCOLTYPE' order by value");

	if ($result->num_rows > 0)
		while ($row = $result->fetch_assoc())
			$dataRows[] = $row;
			
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getAcquisitionTypes($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = [];
	$result = $conn->query("select * from enums where type = 'LIBACQTYPE' order by value");
	
	if ($result->num_rows > 0)
		while ($row = $result->fetch_assoc())
			$dataRows[] = $row;
			
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getPeriodicityTypes($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = [];
	$result = $conn->query("select * from enums where type = 'LIBPERIOD' order by value");
	
	if ($result->num_rows > 0)
		while ($row = $result->fetch_assoc())
			$dataRows[] = $row;
			
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function buildCollectionQuery($baseQuery, $_orderBy, $colTypeId, $searchKeywords, $useLimit = null)
{
	if (file_exists("includes/SearchById.php"))
		require_once("includes/SearchById.php");
	else if (file_exists("../includes/SearchById.php"))
		require_once("../includes/SearchById.php");
	
	$outputInfos = [ "query" => "", "bindParamTypes" => "", "bindParamParamsArray" => [], "colTypeSearch" => false, "search" => false];
	
	$where = (strlen($searchKeywords) > 3) || (isset($colTypeId) && isId($colTypeId)) ? "where " : "";
	
	$whereColType = "";
	if (isset($colTypeId) && isId($colTypeId))
	{
		$whereColType = "(collectionTypeId = ?) ";
		$outputInfos["bindParamTypes"] .= "i";
		$outputInfos["bindParamParamsArray"][] = $colTypeId;
		$outputInfos["colTypeSearch"] = true;
		
		$where .= $whereColType;
	}
	
	$whereSearch = "";
	if (strlen($searchKeywords) > 3 && !isSearchById($searchKeywords))
	{
		$whereSearch = "(MATCH (author, title, cdu, cdd, isbn, publisher_edition, provider) AGAINST (?)) ";
		$outputInfos["bindParamTypes"] .= "s";
		$outputInfos["bindParamParamsArray"][] = $searchKeywords;
		$outputInfos["search"] = true;
		
		if ($whereColType) $where .= " and ";
		$where .= $whereSearch;
	}
	else if (isSearchById($searchKeywords))
	{
		$searchById = new SearchById($searchKeywords, "librarycollection.id");
		$whereSearch = "(" . $searchById->generateSQLWhereConditions() . ") ";
		$outputInfos["bindParamTypes"] .= $searchById->generateBindParamTypes();
		$outputInfos["bindParamParamsArray"] = array_merge($outputInfos["bindParamParamsArray"], $searchById->generateBindParamValues());
		$outputInfos["search"] = true;
		
		if ($whereColType) $where .= " and ";
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
	
	$limit = "";
	if ($useLimit !== null)
	{
		$limit = "limit ?, ?";
		$outputInfos["bindParamTypes"] .= "ii";
		$outputInfos["bindParamParamsArray"][] = $useLimit[0];
		$outputInfos["bindParamParamsArray"][] = $useLimit[1];
	}
			
	$outputInfos["query"] = $baseQuery . $where . $orderBy . $limit;
		
	return $outputInfos;
}

function getCollectionCount($colTypeId, $searchKeywords, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$count = 0;
	
	$baseQuery = "SELECT count(*) FROM librarycollection ";
	$infos = buildCollectionQuery($baseQuery, null, $colTypeId, $searchKeywords, null);
	$query = $infos["query"];
	
	if ($stmt = $conn->prepare($query))
	{
		if (!empty($infos["bindParamTypes"]))
			$stmt->bind_param($infos["bindParamTypes"], ...$infos["bindParamParamsArray"]);
		
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		$count = $result->fetch_row()[0];
	}
	
	if (!$optConnection) $conn->close();
	
	return $count;
}

function getCollectionPartially($page, $numResultsOnPage, $__orderBy, $colTypeId, $searchKeywords, $optConnection = null)
{
	$calc_page = ($page - 1) * $numResultsOnPage;
	
	$_orderBy = ($__orderBy === null || $__orderBy === "") ? "id" : $__orderBy;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	$baseQuery = "SELECT librarycollection.id, colltypeenum.value as collectionTypeName, title, author, cdu, cdd, isbn, edition, volume, copyNumber  
FROM librarycollection 
LEFT JOIN enums colltypeenum ON colltypeenum.type = 'LIBCOLTYPE' and colltypeenum.id = collectionTypeId ";
	
	$infos = buildCollectionQuery($baseQuery, $_orderBy, $colTypeId, $searchKeywords, [ $calc_page, $numResultsOnPage ]);
	$query = $infos["query"];
	
	if ($stmt = $conn->prepare($query))
	{
		if (!empty($infos["bindParamTypes"]))
			$stmt->bind_param($infos["bindParamTypes"], ...$infos["bindParamParamsArray"]);
		
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
	
	$query = "SELECT librarycollection.*, coltypeenum.value as colTypeName, acqtypeenum.value as acqTypeName, periodicityenum.value as periodicityName, users.name as registeredByUserName FROM `librarycollection`
left join enums coltypeenum on coltypeenum.type = 'LIBCOLTYPE' and coltypeenum.id = librarycollection.collectionTypeId
left join enums acqtypeenum on acqtypeenum.type = 'LIBACQTYPE' and acqtypeenum.id = librarycollection.typeAcquisitionId
left join enums periodicityenum on periodicityenum.type = 'LIBPERIOD' and periodicityenum.id = librarycollection.periodicityId 
left join users on users.id = librarycollection.registeredByUserId
WHERE librarycollection.id = ?";
	
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $id);
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

function getLoanListLimited($publicationId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	if ($stmt = $conn->prepare("SELECT libraryborrowedpublications.*, aes_decrypt(libraryusers.name, '$__cryptoKey') as userName,
(returnDatetime is not null and returnDatetime <= now()) as isReturned, (returnDatetime is not null and returnDatetime > DATE_ADD(expectedReturnDatetime, INTERVAL 30 MINUTE)) as returnedLate 	
FROM libraryborrowedpublications
LEFT JOIN libraryusers ON libraryusers.id = libraryborrowedpublications.libUserId
WHERE publicationId = ?
ORDER BY borrowDatetime DESC
LIMIT 10"))
	{
		$stmt->bind_param("i", $publicationId);
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

function getReservationsListLimited($publicationId, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	$query = "SELECT libraryreservations.*, aes_decrypt(libraryusers.name, '$__cryptoKey') as userName, (borrowedPubId is not null) as isFinalized
FROM libraryreservations
LEFT JOIN libraryusers ON libraryusers.id = libraryreservations.libUserId
WHERE publicationId = ?
ORDER BY reservationDatetime DESC
LIMIT 10";
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $publicationId);
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

function updatePublication($postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$affectedRows = 0;
	$query = "UPDATE librarycollection SET collectionTypeId = ?, 
	registrationDate = ?, 
	author = ?, 
	title = ?, 
	cdu = ?, 
	cdd = ?, 
	isbn = ?, 
	local = ?, 
	publisher_edition = ?, 
	number = ?,
	periodicityId = ?,
	month = ?,
	duration = ?,
	items = ?,
	year = ?, 
	edition = ?, 
	volume = ?,
	copyNumber = ?, 
	pageNumber = ?, 
	typeAcquisitionId = ?, 
	price = ?, 
	provider = ?, 
	dateAcquisition = ?, 
	registeredByUserId = ?
	WHERE id = ?";
	if ($stmt = $conn->prepare($query))
	{
		$dateAcquisition = $postData["dateAcquisition"] ? $postData["dateAcquisition"] : null;
		$price = $postData["numPrice"] !== "" ? $postData["numPrice"] : null;
		
		$stmt->bind_param("isssssssssissssssssidssii", $postData["selColType"],
													$postData["dateRegistrationDate"],
													$postData["txtAuthor"],
													$postData["txtTitle"],
													$postData["txtCDU"],
													$postData["txtCDD"],
													$postData["txtISBN"],
													$postData["txtLocal"],
													$postData["txtPublisher_Edition"],
													$postData["txtNumber"],
													$postData["selPeriodicity"],
													$postData["txtMonth"],
													$postData["txtDuration"],
													$postData["txtItems"],
													$postData["txtYear"],
													$postData["txtEdition"],
													$postData["txtVolume"],
													$postData["txtCopyNumber"],
													$postData["txtPageNumber"],
													$postData["selAcquisitionType"],
													$price,
													$postData["txtProvider"],
													$dateAcquisition,
													$postData["hidRegisteredByUserId"],
													$postData["publicationId"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $affectedRows > 0;
}

function createPublication($postData, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$affectedRows = 0;
	$insertId = null;
	$query = "INSERT INTO librarycollection (collectionTypeId, registrationDate, author, title, cdu, cdd, isbn, local, publisher_edition, number, periodicityId, month, duration, items, year, edition, volume, copyNumber, pageNumber, typeAcquisitionId, price, provider, dateAcquisition, registeredByUserId)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	
	if ($stmt = $conn->prepare($query))
	{
		$dateAcquisition = $postData["dateAcquisition"] ? $postData["dateAcquisition"] : null;
		$price = $postData["numPrice"] ? $postData["numPrice"] : null;
		
		$stmt->bind_param("isssssssssissssssssidssi", $postData["selColType"],
													$postData["dateRegistrationDate"],
													$postData["txtAuthor"],
													$postData["txtTitle"],
													$postData["txtCDU"],
													$postData["txtCDD"],
													$postData["txtISBN"],
													$postData["txtLocal"],
													$postData["txtPublisher_Edition"],
													$postData["txtNumber"],
													$postData["selPeriodicity"],
													$postData["txtMonth"],
													$postData["txtDuration"],
													$postData["txtItems"],
													$postData["txtYear"],
													$postData["txtEdition"],
													$postData["txtVolume"],
													$postData["txtCopyNumber"],
													$postData["txtPageNumber"],
													$postData["selAcquisitionType"],
													$price,
													$postData["txtProvider"],
													$dateAcquisition,
													$postData["hidRegisteredByUserId"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$insertId = $conn->insert_id;
		$stmt->close();
	}
		
	if (!$optConnection) $conn->close();
	return [ 'newId' => $insertId, 'isCreated' => $affectedRows > 0];
}

function deletePublication($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$affectedRows = 0;
	$query = "DELETE FROM librarycollection WHERE id = ?";
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	return $affectedRows > 0;
}

function getFullCollection($__orderBy, $colTypeId, $searchKeywords, $optConnection = null)
{
	$_orderBy = ($__orderBy === null || $__orderBy === "") ? "id" : $__orderBy;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
		
	$baseQuery = "SELECT librarycollection.id as ID, colltypeenum.value as 'Cat. acervo', registrationDate as 'Data de registro', author as 'Autor', title as 'Título', cdu, cdd, isbn, local, publisher_edition as 'Editora/edição', number as 'Número', periodicityenum.value as 'Periodicidade', month as 'Mês', duration as 'Duração', items as 'itens', year as 'Ano', edition as 'Edição', volume as 'Volume', copyNumber as 'Exemplar', pageNumber as 'Número de páginas', price as 'Preço', provider as 'Fornecedor', dateAcquisition as 'Data de aquisição', acqtypeenum.value as 'Tipo de aquisição', users.name as 'Responsável pelo cadastro'
FROM librarycollection 
LEFT JOIN enums colltypeenum ON colltypeenum.type = 'LIBCOLTYPE' and colltypeenum.id = collectionTypeId
LEFT join enums acqtypeenum on acqtypeenum.type = 'LIBACQTYPE' and acqtypeenum.id = librarycollection.typeAcquisitionId
LEFT JOIN enums periodicityenum on periodicityenum.type = 'LIBPERIOD' and periodicityenum.id = librarycollection.periodicityId 
LEFT join users on users.id = librarycollection.registeredByUserId ";
	
	$dataRows = null;
	$query = "";
	
	$infos = buildCollectionQuery($baseQuery, $_orderBy, $colTypeId, $searchKeywords, null);
	$query = $infos["query"];
	
	if ($stmt = $conn->prepare($query))
	{
		if (!empty($infos["bindParamTypes"]))
			$stmt->bind_param($infos["bindParamTypes"], ...$infos["bindParamParamsArray"]);
		
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		
		if ($result->num_rows > 0)
		{
			$dataRows = $result->fetch_all(MYSQLI_ASSOC);
		}
		$result->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getFullCollectionForTags($colTypeId, $searchKeywords, $optConnection = null)
{	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$baseQuery = "SELECT id, cdd, edition, copyNumber, author, title FROM librarycollection ";
	$dataRows = null;
	$infos = buildCollectionQuery($baseQuery, null, $colTypeId, $searchKeywords, null);
	
	if ($stmt = $conn->prepare($infos["query"]))
	{
		if (!empty($infos["bindParamTypes"]))
			$stmt->bind_param($infos["bindParamTypes"], ...$infos["bindParamParamsArray"]);
		
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