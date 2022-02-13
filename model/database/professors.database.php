<?php

require_once("database.php");


function getSingleProfessor($id, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	
	if($stmt = $conn->prepare("select id, aes_decrypt(name, '$__cryptoKey') as name, aes_decrypt(email, '$__cryptoKey') as email, aes_decrypt(telephone, '$__cryptoKey') as telephone, aes_decrypt(schoolingLevel, '$__cryptoKey') as schoolingLevel, aes_decrypt(topicsOfInterest, '$__cryptoKey') as topicsOfInterest, aes_decrypt(lattesLink, '$__cryptoKey') as lattesLink, agreesWithConsentForm, consentForm, registrationDate from professors where id = ?"))
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

function getProfessorsCount($searchKeywords, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$totalRecords = 0;
	
	if (strlen($searchKeywords) > 3)
	{
		$query = "select count(*) from professors where convert(aes_decrypt(name, '$__cryptoKey') using 'UTF8MB4') like ? or convert(aes_decrypt(email, '$__cryptoKey') using 'UTF8MB4') like ?";
		
		if($stmt = $conn->prepare($query))
		{
			$paramSearch = "%" . $searchKeywords . "%";
			$stmt->bind_param("ss", $paramSearch, $paramSearch);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			
			$totalRecords = $result->fetch_row()[0];
		}
	}
	else
		$totalRecords = $conn->query('select count(*) from professors')->fetch_row()[0];
	
	if (!$optConnection) $conn->close();
	
	return $totalRecords;
}

function getProfessorsPartially($page, $numResultsOnPage, $_orderBy, $searchKeywords, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$orderBy = ($_orderBy === null) ? "name" : $_orderBy;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = [];
	$queryBase = "select id, aes_decrypt(name, '$__cryptoKey') as name, aes_decrypt(email, '$__cryptoKey') as email, registrationDate from professors ";
	
	$querySearch = "";
	if (strlen($searchKeywords) > 3)
		$querySearch = "where convert(aes_decrypt(name, '$__cryptoKey') using 'UTF8MB4') like ? or convert(aes_decrypt(email, '$__cryptoKey') using 'UTF8MB4') like ? ";
	else
		$querySearch = "";
	
	$queryOrderBy = "";
	if ($orderBy === "name")
		$queryOrderBy = "order by name limit ?, ?";
	else
		$queryOrderBy = "order by registrationDate desc limit ?, ?";
	
	
	$query = $queryBase . $querySearch . $queryOrderBy;
	if($stmt = $conn->prepare($query))
		{
			$calc_page = ($page - 1) * $numResultsOnPage;
			if ($querySearch)
			{
				$paramSearch = "%" . $searchKeywords . "%";
				$stmt->bind_param("ssii", $paramSearch, $paramSearch, $calc_page, $numResultsOnPage);
			}
			else
				$stmt->bind_param("ii", $calc_page, $numResultsOnPage);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			
			if ($result->num_rows > 0)
				while ($row = $result->fetch_assoc())
				{
					array_push($dataRows, $row);
				}
		}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}

function updateProfessor($postData)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = createConnectionAsEditor();
	
	$affectedRows = 0;
	if($stmt = $conn->prepare("update professors set name = aes_encrypt(?, '$__cryptoKey'), email = aes_encrypt(?, '$__cryptoKey'), telephone = aes_encrypt(?, '$__cryptoKey'), schoolingLevel = aes_encrypt(?, '$__cryptoKey'), topicsOfInterest = aes_encrypt(?, '$__cryptoKey'), lattesLink = aes_encrypt(?, '$__cryptoKey') where id = ?"))
	{
		$stmt->bind_param("ssssssi", $postData["txtName"], $postData["txtEmail"], $postData["txtTelephone"], $postData["txtSchoolingLevel"], $postData["txtTopicsOfInterest"], $postData["txtLattesLink"], $postData["profId"]);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	$conn->close();
	
	return $affectedRows > 0;
}

function deleteProfessor($id)
{
	$conn = createConnectionAsEditor();
	
	$affectedRows = 0;
	if($stmt = $conn->prepare("delete from professors where id = ?"))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();
	}
	
	$conn->close();
	
	return $affectedRows > 0;
}

function getFullProfessors($_orderBy, $searchKeywords, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$orderBy = ($_orderBy === null) ? "name" : $_orderBy;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = [];
	$queryBase = "select id, aes_decrypt(name, '$__cryptoKey') as Nome, aes_decrypt(email, '$__cryptoKey') as Email, aes_decrypt(telephone, '$__cryptoKey') as Telefone, aes_decrypt(schoolingLevel, '$__cryptoKey') as Escolaridade, aes_decrypt(topicsOfInterest, '$__cryptoKey') as 'Temas de interesse', aes_decrypt(lattesLink, '$__cryptoKey') as 'Plataforma Lattes', agreesWithConsentForm as 'Concorda com o termo de consentimento', consentForm as 'Termo de consentimento', registrationDate as 'Data de registro' from professors ";
	
	$querySearch = "";
	if (strlen($searchKeywords) > 3)
		$querySearch = "where convert(aes_decrypt(name, '$__cryptoKey') using 'UTF8MB4') like ? or convert(aes_decrypt(email, '$__cryptoKey') using 'UTF8MB4') like ? ";
	else
		$querySearch = "";
	
	$queryOrderBy = "";
	if ($orderBy === "name")
		$queryOrderBy = "order by name";
	else
		$queryOrderBy = "order by registrationDate desc";
	
	$query = $queryBase . $querySearch . $queryOrderBy;
	
	if($stmt = $conn->prepare($query))
		{
			if ($querySearch)
			{
				$paramSearch = "%" . $searchKeywords . "%";
				$stmt->bind_param("ss", $paramSearch, $paramSearch);
			}
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			
			if ($result->num_rows > 0)
				while ($row = $result->fetch_assoc())
				{
					$dataRows[] = $row;
				}
		}
	
	if (!$optConnection) $conn->close();
	
	return $dataRows;
}