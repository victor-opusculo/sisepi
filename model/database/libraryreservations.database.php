<?php
require_once("database.php");
require_once("crypto.php");
require_once("ext.libraryreservations.database.php");

function buildReservationsQuery($baseQuery, $_orderBy, $searchKeywords, $useLimit = true, $exportReportMode = false)
{
	$__cryptoKey = crypto_Key;
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
		case "id": $orderBy = "order by libraryreservations.id ASC "; break;
		case "title": $orderBy = "order by librarycollection.title ASC "; break;
		case "userName": $orderBy = $exportReportMode ? "order by `Usuário` ASC " : "order by userName ASC "; break;
		case "reservationDatetime": $orderBy = "order by reservationDatetime DESC "; break;
		case "isFinalized": $orderBy = $exportReportMode ? "order by `Atendida?` ASC " : "order by isFinalized ASC "; break;
	}
	
	$limit = $useLimit ? "limit ?, ?" : "";
	
	$outputInfos["query"] = $baseQuery . $where . $orderBy . $limit;
	
	return $outputInfos;
}

function getReservationsCount($searchKeywords, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	checkForInvalidReservations($conn); //Invalidate old reservations
	
	$count = 0;
	$baseQuery = "SELECT count(*) 
FROM libraryreservations
left join librarycollection on librarycollection.id = libraryreservations.publicationId
left join libraryusers on libraryusers.id = libraryreservations.libUserId ";

	$infos = buildReservationsQuery($baseQuery, null, $searchKeywords, false);
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

function getReservationsPartially($page, $numResultsOnPage, $__orderBy, $searchKeywords, $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	$_orderBy = ($__orderBy === null || $__orderBy === "") ? "reservationDatetime" : $__orderBy;

	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$baseQuery = "SELECT libraryreservations.id, libraryreservations.reservationDatetime, librarycollection.title, aes_decrypt(libraryusers.name, '$__cryptoKey') as userName, 
	IF(libraryreservations.borrowedPubId is not null, 1, IF(libraryreservations.invalidatedDatetime is not null, -1, 0)) as isFinalized
FROM libraryreservations
LEFT JOIN librarycollection ON librarycollection.id = libraryreservations.publicationId
LEFT JOIN libraryusers ON libraryusers.id = libraryreservations.libUserId ";

	$infos = buildReservationsQuery($baseQuery, $_orderBy, $searchKeywords, true);
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

function getSingleReservation($id, $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "SELECT libraryreservations.id, publicationId, libUserId, reservationDatetime, librarycollection.title, borrowedPubId, invalidatedDatetime, 
aes_decrypt(libraryusers.name, '$__cryptoKey') as userName
FROM libraryreservations
LEFT JOIN librarycollection ON librarycollection.id = libraryreservations.publicationId
LEFT JOIN libraryusers ON libraryusers.id = libraryreservations.libUserId
WHERE libraryreservations.id = ?";
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
			throw new Exception("Registro não localizado.");
		}
	}
	
	if (!$optConnection) $conn->close();
	return $dataRow;
}

function checkForInvalidReservations($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "select publicationId, count(publicationId) as pubCount from libraryreservations where borrowedPubId is null and invalidatedDatetime is null group by publicationId";
	$result = $conn->query($query);
	if ($result->num_rows > 0)
		while ($row = $result->fetch_assoc())
		{
			invalidatePendingAndOldReservations($row["publicationId"], $conn);
		}
	
	if (!$optConnection) $conn->close();
}

function getFullReservationsList($__orderBy, $searchKeywords, $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	$_orderBy = ($__orderBy === null || $__orderBy === "") ? "reservationDatetime" : $__orderBy;
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$baseQuery = "select libraryreservations.id as 'ID', 
libraryreservations.publicationId as 'ID da publicação', 
librarycollection.title as 'Publicação',
libraryreservations.libUserId as 'ID de usuário',
aes_decrypt(libraryusers.name, '$__cryptoKey') as 'Usuário',
libraryreservations.reservationDatetime as 'Data de reserva',
libraryreservations.borrowedPubId as 'Empréstimo resultante (ID)',
IF (libraryreservations.borrowedPubId is not null, '1 - Sim', IF (libraryreservations.invalidatedDatetime is not null, '0 - Invalidada', 'Aguardando')) as 'Atendida?'
FROM libraryreservations
LEFT JOIN librarycollection ON librarycollection.id = libraryreservations.publicationId
LEFT JOIN libraryusers ON libraryusers.id = libraryreservations.libUserId ";
	
	$infos = buildReservationsQuery($baseQuery, $_orderBy, $searchKeywords, false, true);
	
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

function getSinglePublication($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = "SELECT librarycollection.id
FROM `librarycollection`
WHERE librarycollection.id = ?";
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
			throw new Exception("Publicação não localizada.");
		}
	}
	
	if (!$optConnection) $conn->close();
	
	return $dataRow;
}

function getSingleUser($id, $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = "SELECT libraryusers.id 
FROM libraryusers
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

function checkIfReservationExists($publicationId, $userId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$exists = false;
	$query = "SELECT * FROM libraryreservations WHERE publicationId = ? AND libUserId = ? AND borrowedPubId IS NULL AND invalidatedDatetime IS NULL";
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("ii", $publicationId, $userId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$exists = ($result->num_rows > 0);
	}
	
	if (!$optConnection) $conn->close();
	return $exists;
}

function createReservation($publicationId, $userId, $reservationDatetime, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$canCreate = true;
	$reasonForNotCreating = "";
	
	$insertedId = null;
	$affectedRows = 0;
	
	invalidatePendingAndOldReservations($publicationId, $conn);
	
	$pubDR = getSinglePublication($publicationId, $conn);
	$userDR = getSingleUser($userId, $conn);
	$reservationExists = checkIfReservationExists($publicationId, $userId, $conn);
	if ($pubDR === null)
	{
		$canCreate = false;
		$reasonForNotCreating = "Erro: Publicação não localizada.";
	}
	else if ($userDR === null)
	{
		$canCreate = false;
		$reasonForNotCreating = "Erro: Usuário não localizado.";
	}
	else if ($reservationExists)
	{
		$canCreate = false;
		$reasonForNotCreating = "Erro: Este usuário já têm uma reserva válida e não atendida para esta publicação.";
	}
	
	if ($canCreate)
	{
		$query = "INSERT INTO libraryreservations (publicationId, libUserId, reservationDatetime) VALUES (?, ?, ?)";
		if ($stmt = $conn->prepare($query))
		{
			$stmt->bind_param("iis", $publicationId, $userId, $reservationDatetime);
			$stmt->execute();
			$affectedRows = $stmt->affected_rows;
			$insertedId = $conn->insert_id;
			$stmt->close();
		}
	}
	else
	{
		if (!$optConnection) $conn->close();
		throw new Exception($reasonForNotCreating);
	}
	
	if (!$optConnection) $conn->close();
	return ['newId' => $insertedId, 'isCreated' => $affectedRows > 0];
}

function deleteReservation($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$affectedRows = 0;
	$query = "DELETE FROM libraryreservations WHERE id = ?";
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