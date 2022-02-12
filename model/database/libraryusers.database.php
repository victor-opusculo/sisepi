<?php
require_once("database.php");
require_once("crypto.php");

function buildUsersQuery($baseQuery, $_orderBy, $searchKeywords, $useLimit = true, $exportReportMode = false)
{
	$__cryptoKey = crypto_Key;
	$outputInfos = [ "query" => "", "search" => false ];
	
	$where = strlen($searchKeywords) > 3 ? "where " : "";
	
	$whereSearch = "";
	if (strlen($searchKeywords) > 3)
	{
		$whereSearch = "convert(aes_decrypt(libraryusers.name, '$__cryptoKey') using 'UTF8MB4') LIKE ? OR
		convert(aes_decrypt(libraryusers.email, '$__cryptoKey') using 'UTF8MB4') LIKE ? ";
		$outputInfos["search"] = true;
		$where .= $whereSearch;
	}
	
	$orderBy = "";
	switch ($_orderBy)
	{
		case "id": $orderBy = "order by id ASC "; break;
		case "name": $orderBy = $exportReportMode ? "order by Nome ASC " : "order by name ASC "; break;
		case "email": $orderBy = $exportReportMode ? "order by `E-mail` ASC " : "order by email ASC "; break;
		case "typeName": $orderBy = $exportReportMode ? "order by Tipo ASC " : "order by typeName ASC "; break;
	}
	
	$limit = $useLimit ? "limit ?, ?" : "";
	
	$outputInfos["query"] = $baseQuery . $where . $orderBy . $limit;
	
	return $outputInfos;
}

function getUsersCount($searchKeywords, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$baseQuery = "SELECT COUNT(*) FROM libraryusers ";
	
	$infos = buildUsersQuery($baseQuery, null, $searchKeywords, false);
	$count = 0;
	
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

function getUsersPartially($page, $numResultsOnPage, $__orderBy, $searchKeywords, $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	$_orderBy = ($__orderBy === null || $__orderBy === "") ? "id" : $__orderBy;
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$baseQuery = "SELECT libraryusers.id, aes_decrypt(name, '$__cryptoKey') as name, aes_decrypt(email, '$__cryptoKey') as email, enums.value as typeName
FROM libraryusers
LEFT JOIN enums ON enums.type = 'LIBUSRTYPE' AND enums.id = libraryusers.typeId ";
	
	$infos = buildUsersQuery($baseQuery, $_orderBy, $searchKeywords, true);
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

function getSingleUser($id, $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$query = "SELECT libraryusers.id, aes_decrypt(name, '$__cryptoKey') as name,
	aes_decrypt(CMI_Department, '$__cryptoKey') as CMI_Department,
	aes_decrypt(CMI_RegNumber, '$__cryptoKey') as CMI_RegNumber,
	aes_decrypt(telephone, '$__cryptoKey') as telephone,
	aes_decrypt(email, '$__cryptoKey') as email,
	enums.value as typeName,
	typeId,
	agreesWithConsentForm,
	consentForm
	FROM libraryusers
	LEFT JOIN enums ON enums.type = 'LIBUSRTYPE' AND enums.id = libraryusers.typeId
	WHERE libraryusers.id = ?";
	
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

function getUserTypes($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$dataRows = [];
	$result = $conn->query("SELECT id, value FROM enums WHERE type = 'LIBUSRTYPE'");
	
	if ($result->num_rows > 0)
		while ($row = $result->fetch_assoc())
			$dataRows[] = $row;
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function getLoanListLimited($userId, $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	if ($stmt = $conn->prepare("SELECT libraryborrowedpublications.*, librarycollection.title, (returnDatetime is not null and returnDatetime <= now()) as isReturned, (returnDatetime is not null and returnDatetime > DATE_ADD(expectedReturnDatetime, INTERVAL 30 MINUTE)) as returnedLate  
FROM libraryborrowedpublications
LEFT JOIN librarycollection ON librarycollection.id = libraryborrowedpublications.publicationId
WHERE libUserId = ? 
ORDER BY borrowDatetime DESC
LIMIT 10"))
	{
		$stmt->bind_param("i", $userId);
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

function getReservationsListLimited($userId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = null;
	$query = "SELECT libraryreservations.*, librarycollection.title, (borrowedPubId is not null) as isFinalized
FROM libraryreservations
LEFT JOIN librarycollection ON librarycollection.id = libraryreservations.publicationId
WHERE libUserId = ?
ORDER BY reservationDatetime DESC
LIMIT 10";
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $userId);
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

function updateUser($postData, $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$affectedRows = 0;
	$query = "UPDATE libraryusers SET 
name = aes_encrypt(?, '$__cryptoKey'),
CMI_Department = aes_encrypt(?, '$__cryptoKey'),
CMI_RegNumber = aes_encrypt(?, '$__cryptoKey'),
telephone = aes_encrypt(?, '$__cryptoKey'),
email = aes_encrypt(?, '$__cryptoKey'),
typeId = ?,
consentForm = ?
WHERE id = ?";

	if ($stmt = $conn->prepare($query))
	{
		$consentFormValue = isset($postData["chkUpdateConsentForm"]) && $postData["chkUpdateConsentForm"] ? $postData["chkUpdateConsentForm"] : $postData["hidRegisteredOldConsentForm"];
		$stmt->bind_param("sssssisi", $postData["txtName"], $postData["txtCMIDepartment"], $postData["txtCMIRegNumber"], $postData["txtTelephone"], $postData["txtEmail"], $postData["selUserType"], $consentFormValue, $postData["userId"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return $affectedRows > 0;
}

function createUser($postData, $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = 
"INSERT into libraryusers (name, CMI_Department, CMI_RegNumber, telephone, email, typeId, agreesWithConsentForm, consentForm) VALUES 
(
    aes_encrypt(?, '$__cryptoKey'),
    aes_encrypt(?, '$__cryptoKey'),
	aes_encrypt(?, '$__cryptoKey'),
    aes_encrypt(?, '$__cryptoKey'),
    aes_encrypt(?, '$__cryptoKey'),
    ?,
    ?,
    ?
);";
	$affectedRows = 0;
	$insertedId = null;
	if ($stmt = $conn->prepare($query))
	{
		$chkAgreesWithConsentForm = isset($postData["chkAgreesWithConsentForm"]) ? 1 : 0;
		
		$stmt->bind_param("sssssiis", $postData["txtName"], $postData["txtCMIDepartment"], $postData["txtCMIRegNumber"], $postData["txtTelephone"], $postData["txtEmail"], $postData["selUserType"], $chkAgreesWithConsentForm,
		$postData["hidConsentForm"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$insertedId = $conn->insert_id;
		$stmt->close();
	}
	
	if (!$optConnection) $conn->close();
	
	return [ "affectedRows" => $affectedRows, "newId" => $insertedId ];
}

function deleteUser($id, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$affectedRows = 0;
	$query = "DELETE FROM libraryusers WHERE id = ?";
	
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

function getFullUsers($__orderBy, $searchKeywords, $optConnection = null)
{
	$__cryptoKey = crypto_Key;
	$_orderBy = ($__orderBy === null || $__orderBy === "") ? "id" : $__orderBy;
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$baseQuery = "SELECT libraryusers.id, 
	aes_decrypt(name, '$__cryptoKey') as 'Nome', 
	aes_decrypt(CMI_Department, '$__cryptoKey') as 'Setor',
	aes_decrypt(CMI_RegNumber, '$__cryptoKey') as 'Matrícula',
	aes_decrypt(telephone, '$__cryptoKey') as 'Telefone',
	aes_decrypt(email, '$__cryptoKey') as 'E-mail', 
	enumusertype.value as 'Tipo',
	if(libraryusers.agreesWithConsentForm, 'Sim', 'Não') as 'Concorda com o termo?',
	libraryusers.consentForm as 'Termo de consentimento'
FROM libraryusers
LEFT JOIN enums enumusertype ON enumusertype.type = 'LIBUSRTYPE' AND enumusertype.id = libraryusers.typeId ";
	
	$infos = buildUsersQuery($baseQuery, $_orderBy, $searchKeywords, false, true);
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

function getLateDevolutionsCount($userId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = "SELECT COUNT(id) FROM `libraryborrowedpublications` WHERE libUserId = ? AND (returnDatetime > DATE_ADD(expectedReturnDatetime, INTERVAL 30 MINUTE) AND returnDatetime > DATE_SUB(NOW(), INTERVAL 90 DAY))";
	$count = 0;
	if ($stmt = $conn->prepare($query))
	{
		$stmt->bind_param("i", $userId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		if ($result->num_rows > 0)
			$count = $result->fetch_row()[0];
		$result->close();
	}
	
	if (!$optConnection) $conn->close();
	return $count;
}