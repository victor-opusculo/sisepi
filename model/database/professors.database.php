<?php
require_once("database.php");
require_once("professors.uploadFiles.php");

function getSingleProfessor($id, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRow = null;
	
	if($stmt = $conn->prepare("select id, 
	aes_decrypt(name, '$__cryptoKey') as name, 
	aes_decrypt(email, '$__cryptoKey') as email, 
	aes_decrypt(telephone, '$__cryptoKey') as telephone, 
	aes_decrypt(schoolingLevel, '$__cryptoKey') as schoolingLevel, 
	aes_decrypt(topicsOfInterest, '$__cryptoKey') as topicsOfInterest,
	aes_decrypt(lattesLink, '$__cryptoKey') as lattesLink, 
	collectInss,
    aes_decrypt(inssCollectInfosJson, '$__cryptoKey') as inssCollectInfosJson,
    aes_decrypt(personalDocsJson, '$__cryptoKey') as personalDocsJson,
    aes_decrypt(homeAddressJson, '$__cryptoKey') as homeAddressJson,
    aes_decrypt(miniResumeJson, '$__cryptoKey') as miniResumeJson,
    aes_decrypt(bankDataJson, '$__cryptoKey') as bankDataJson,
	agreesWithConsentForm, 
	consentForm, 
	registrationDate from professors where id = ?"))
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

function updateProfessor($dbEntity, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
    $__cryptoKey = getCryptoKey();

    $dbEntity->setCryptoKey($__cryptoKey);
    $colsAndFields = $dbEntity->generateSQLUpdateCommandColumnsAndFields();
    $query = "UPDATE professors SET $colsAndFields[setColumnsAndFields] WHERE $colsAndFields[whereCondition] ";
    $stmt = $conn->prepare($query);
    $typesAndValues = $dbEntity->generateBindParamTypesAndValues();
    $stmt->bind_param($typesAndValues['types'], ...$typesAndValues['values']);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    if (!$optConnection) $conn->close();
    return $affectedRows > 0;
}

function deleteProfessor($id)
{
	$conn = createConnectionAsEditor();
	
	$affectedRows = 0;
	if($stmt = $conn->prepare("delete from professordocsattachments where professorId = ?"))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();
	}

	if($stmt = $conn->prepare("delete from professors where id = ?"))
	{
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$affectedRows += $stmt->affected_rows;
		$stmt->close();
	}
	
	$conn->close();

	if ($affectedRows > 0)
	{
		cleanDocsFolder($id);
		checkForEmptyDocsDir($id);
		checkForEmptyProfessorDir($id);
	}
	
	return $affectedRows > 0;
}

function getFullProfessors($_orderBy, $searchKeywords, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
	
	$orderBy = ($_orderBy === null) ? "name" : $_orderBy;
	
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	
	$dataRows = [];
	$queryBase = "select id, aes_decrypt(name, '$__cryptoKey') as name, 
	aes_decrypt(email, '$__cryptoKey') as email,
	aes_decrypt(telephone, '$__cryptoKey') as telephone, 
	aes_decrypt(schoolingLevel, '$__cryptoKey') as schoolingLevel, 
	aes_decrypt(topicsOfInterest, '$__cryptoKey') as topicsOfInterest, 
	aes_decrypt(lattesLink, '$__cryptoKey') as lattesLink, 
	collectInss,
	aes_decrypt(personalDocsJson, '$__cryptoKey') as personalDocsJson,
    aes_decrypt(homeAddressJson, '$__cryptoKey') as homeAddressJson,
    aes_decrypt(bankDataJson, '$__cryptoKey') as bankDataJson,
	agreesWithConsentForm, 
	consentForm, 
	registrationDate
	from professors ";
	
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

function getProfessorPersonalDocs($professorId, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "SELECT professordocsattachments.*, JSON_UNQUOTE(JSON_EXTRACT(settings.value, CONCAT('$.', professordocsattachments.docType, '.label'))) as typeName,
	IF (JSON_TYPE(JSON_EXTRACT(settings.value, CONCAT('$.', professordocsattachments.docType, '.expiresAfterDays'))) = 'NULL',
	NULL,
	JSON_EXTRACT(settings.value, CONCAT('$.', professordocsattachments.docType, '.expiresAfterDays'))) as expiresAfterDays
	 FROM `professordocsattachments` 
	inner join settings on settings.name = 'PROFESSORS_DOCUMENT_TYPES'
	WHERE professorId = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $professorId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$dataRows = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : null;
	$result->close();

	if (!$optConnection) $conn->close();
	return $dataRows;
}

function updateUploadedPersonalDocs($professorId, $changesReportJson, $filesPostData, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $changesReportObj = json_decode($changesReportJson);

	$totalFilesCount = count($changesReportObj->create ?? []) + count($changesReportObj->update ?? []);
    if ($totalFilesCount > 10)
        throw new Exception("Você ultrapassou o limite máximo de 10 arquivos.");

    $affectedRows = 0;
    if ($changesReportObj->delete)
        foreach ($changesReportObj->delete as $deleteReg)
        {
            $queryGetFilename = "SELECT fileName from professordocsattachments WHERE id = ? ";
            $stmt = $conn->prepare($queryGetFilename);
            $stmt->bind_param("i", $deleteReg->id);
            $stmt->execute();
            $fileToDelete = $stmt->get_result()->fetch_row()[0];
            $stmt->close();

            $query = "DELETE from professordocsattachments WHERE id = ? ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $deleteReg->id);
            $stmt->execute();
            if ($stmt->affected_rows > 0 && $fileToDelete) deleteDocsFile($professorId, $fileToDelete);
            $affectedRows += $stmt->affected_rows;
            $stmt->close();
        }

    if ($changesReportObj->create)
        foreach ($changesReportObj->create as $createReg)
        {
            $query = "INSERT into professordocsattachments (professorId, docType, fileName) VALUES (?, ?, ?) ";
            $stmt = $conn->prepare($query);
            $fileName = basename($filesPostData[$createReg->fileInputElementName]['name']);
            $stmt->bind_param("iss", $professorId, $createReg->docType, $fileName);
            $uploadResult = uploadPersonalDocFile($professorId, $filesPostData, $createReg->fileInputElementName);
            if ($uploadResult)
            {
                $stmt->execute();
                $affectedRows += $stmt->affected_rows;
            }
            $stmt->close();
        }

    if ($changesReportObj->update)
        foreach ($changesReportObj->update as $updateReg)
        {
            $query = "UPDATE professordocsattachments SET professorId = ?, docType = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isi", $professorId, $updateReg->docType, $updateReg->id);
            $stmt->execute();
            $affectedRows += $stmt->affected_rows;
            $stmt->close();
        }

    if (!$optConnection) $conn->close();

	checkForEmptyDocsDir($professorId);
	checkForEmptyProfessorDir($professorId);

    return $affectedRows > 0;
}