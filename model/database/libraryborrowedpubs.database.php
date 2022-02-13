<?php
require_once("database.php");

require_once("ext.libraryreservations.database.php");

function buildBorrowedPubsQuery($baseQuery, $_orderBy, $searchKeywords, $useLimit = true, $exportReportMode = false)
{
	$__cryptoKey = getCryptoKey();
	$outputInfos = [ "query" => "", "search" => false ];
	
	$where = strlen($searchKeywords) > 3 ? "where " : "";
	
	$whereSearch = "";
	if (strlen($searchKeywords) > 3)
	{
		$whereSearch = "librarycollection.title like ? OR CONVERT(aes_decrypt(libraryusers.name, '$__cryptoKey') using 'UTF8MB4') like ? ";
		$outputInfos["search"] = true;
		$where .= $whereSearch;
	}
	
	$orderBy = "";
	switch ($_orderBy)
	{
		case "id": $orderBy = "order by libraryborrowedpublications.id ASC "; break;
		case "title": $orderBy = "order by librarycollection.title ASC "; break;
		case "userName": $orderBy = $exportReportMode ? "order by `Usuário` " : "order by userName ASC "; break;
		case "borrowDatetime": $orderBy = "order by borrowDatetime DESC "; break;
		case "expectedReturnDatetime": $orderBy = "order by expectedReturnDatetime DESC "; break;
		case "isReturned": $orderBy = $exportReportMode ? "order by `Data de retorno` ASC " : "order by isReturned ASC "; break;
	}
	
	$limit = $useLimit ? "limit ?, ?" : "";
	
	$outputInfos["query"] = $baseQuery . $where . $orderBy . $limit;
	
	return $outputInfos;
}

function getBorrowedPubsCount($searchKeywords, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$count = 0;
	$baseQuery = "SELECT count(*) 
FROM libraryborrowedpublications
left join librarycollection on librarycollection.id = libraryborrowedpublications.publicationId
left join libraryusers on libraryusers.id = libraryborrowedpublications.libUserId ";
	
	$infos = buildBorrowedPubsQuery($baseQuery, null, $searchKeywords, false);
	if ($stmt = $conn->prepare($infos["query"]))
	{
		if ($infos["search"])
		{
			$searchParam = "%" . $searchKeywords . "%";
			$stmt->bind_param("ss", $searchParam, $searchParam);
		}
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$count = $result->fetch_row()[0];
	}
	
	if (!$optConnection) $conn->close();
	
	return $count;
}

function getBorrowedPubsPartially($page, $numResultsOnPage, $__orderBy, $searchKeywords, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$_orderBy = ($__orderBy === null || $__orderBy === "") ? "borrowDatetime" : $__orderBy;
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$baseQuery = "SELECT libraryborrowedpublications.id, librarycollection.title, aes_decrypt(libraryusers.name, '$__cryptoKey') as userName, borrowDatetime, expectedReturnDatetime, (returnDatetime is not null and returnDatetime <= now()) as isReturned, (returnDatetime is not null and returnDatetime > DATE_ADD(expectedReturnDatetime, INTERVAL 30 MINUTE)) as returnedLate 
FROM libraryborrowedpublications 
left join librarycollection on librarycollection.id = libraryborrowedpublications.publicationId
left join libraryusers on libraryusers.id = libraryborrowedpublications.libUserId ";

	$infos = buildBorrowedPubsQuery($baseQuery, $_orderBy, $searchKeywords, true);
	$dataRows = null;
	if ($stmt = $conn->prepare($infos["query"]))
	{
		$calc_page = ($page - 1) * $numResultsOnPage;
		if ($infos["search"])
		{
			$searchParam = "%" . $searchKeywords . "%";
			$stmt->bind_param("ssii", $searchParam, $searchParam, $calc_page, $numResultsOnPage);
		}
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

function getFullBorrowedPubs($__orderBy, $searchKeywords, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$_orderBy = ($__orderBy === null || $__orderBy === "") ? "id" : $__orderBy;
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$baseQuery = "SELECT libraryborrowedpublications.id as 'ID', 
	libraryborrowedpublications.publicationId as 'ID da publicação',
	librarycollection.title as 'Título da publicação',
	libraryborrowedpublications.libUserId as 'ID do usuário', 
	aes_decrypt(libraryusers.name, '$__cryptoKey') as 'Usuário', 
	borrowDatetime as 'Data do empréstimo', 
	expectedReturnDatetime as 'Retorno previsto', 
	returnDatetime as 'Data de retorno' 
FROM libraryborrowedpublications 
left join librarycollection on librarycollection.id = libraryborrowedpublications.publicationId
left join libraryusers on libraryusers.id = libraryborrowedpublications.libUserId ";

	$infos = buildBorrowedPubsQuery($baseQuery, $_orderBy, $searchKeywords, false, true);
	$dataRows = null;
	if ($stmt = $conn->prepare($infos["query"]))
	{
		if ($infos["search"])
		{
			$searchParam = "%" . $searchKeywords . "%";
			$stmt->bind_param("ss", $searchParam, $searchParam);
		}
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

function getSingleBorrowedPub($id, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = "SELECT libraryborrowedpublications.*, librarycollection.title, aes_decrypt(libraryusers.name, '$__cryptoKey') as userName
FROM libraryborrowedpublications 
left join librarycollection on librarycollection.id = libraryborrowedpublications.publicationId
left join libraryusers on libraryusers.id = libraryborrowedpublications.libUserId
WHERE libraryborrowedpublications.id = ? ";
	
	$dataRow = null;
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		if ($result->num_rows > 0)
			$dataRow = $result->fetch_assoc();
		else
			throw new Exception("Registro não localizado.");
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function finalizeLoan($id, $finalizeOnDate, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "UPDATE libraryborrowedpublications SET returnDatetime = ? WHERE id = ?";
	$affectedRows = 0;
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("si", $finalizeOnDate, $id);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $affectedRows > 0;
}

function getSinglePublication($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = "SELECT librarycollection.id, author, title, coltypeenum.value as colTypeName, publisher_edition, volume, copyNumber,
if((select count(id) from libraryborrowedpublications where publicationId = ? AND returnDatetime is null) = 0, 1, 0) as isAvailable
FROM `librarycollection`
left join enums coltypeenum on coltypeenum.type = 'LIBCOLTYPE' and coltypeenum.id = librarycollection.collectionTypeId
WHERE librarycollection.id = ?";
	$dataRow = null;
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("ii", $id, $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		if ($result->num_rows > 0)
			$dataRow = $result->fetch_assoc();
		else
		{
			if (!$optConnection) $conn->close();
			throw new Exception("Publicação não localizada.");
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function getSingleUser($id, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = "SELECT libraryusers.id, 
aes_decrypt(name, '$__cryptoKey') as name, 
aes_decrypt(email, '$__cryptoKey') as email, 
aes_decrypt(telephone, '$__cryptoKey') as telephone, 
enums.value as typeName
FROM libraryusers
LEFT JOIN enums ON enums.type = 'LIBUSRTYPE' AND enums.id = libraryusers.typeId
WHERE libraryusers.id = ?;";
	$dataRow = null;
	
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		if ($result->num_rows > 0)
			$dataRow = $result->fetch_assoc();
		else
		{
			if (!$optConnection) $conn->close();
			throw new Exception("Usuário não localizado.");
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function createLoan($pubId, $userId, $borrowDatetime, $expectedReturnDatetime, $chkSkipReservations, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$canCreate = true;
	$reasonForNotCreating = "";
	
	$affectedRows = 0;
	$bpubNewId = null;
	$pubDR = getSinglePublication($pubId, $conn);
	$userDR = getSingleUser($userId, $conn);
	$resultCheckReservation = checkForReservations($pubId, $userId, $conn);
	if ($pubDR === null)
	{
		$canCreate = false;
		$reasonForNotCreating = "Erro: Publicação não localizada.";
	}
	else if (!(bool)$pubDR["isAvailable"])
	{
		$canCreate = false;
		$reasonForNotCreating = "Erro: Esta publicação não está disponível para empréstimo. Há outro empréstimo não finalizado.";
	}
	else if ($userDR === null)
	{
		$canCreate = false;
		$reasonForNotCreating = "Erro: Usuário não localizado.";
	}
	else if ($resultCheckReservation === false && $chkSkipReservations === false)
	{
		$canCreate = false;
		$reasonForNotCreating = "Erro: Existe uma reserva pendente de um usuário diferente do usuário escolhido.";
	}
	
	if ($canCreate)
	{
		$query = "INSERT INTO libraryborrowedpublications (publicationId, libUserId, borrowDatetime, expectedReturnDatetime) VALUES (?, ?, ?, ?)";
		if($stmt = $conn->prepare($query))
		{
			$stmt->bind_param("iiss", $pubId, $userId, $borrowDatetime, $expectedReturnDatetime);
			$stmt->execute();
			$affectedRows += $stmt->affected_rows;
			$stmt->close();
			$bpubNewId = $conn->insert_id;
			
			if (is_numeric($resultCheckReservation))
				$affectedRows += finalizeReservation($resultCheckReservation, $bpubNewId);
		}
	}
	else
	{
		if (!$optConnection) $conn->close();
		throw new Exception($reasonForNotCreating);
	}
	
	if (!$optConnection) $conn->close();
	
	return [ "isCreated" => ($affectedRows > 0), "newId" => $bpubNewId ];
}