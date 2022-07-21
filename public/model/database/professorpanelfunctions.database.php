<?php
require_once("database.php");
require_once("professors.uploadFiles.php");

function formatProfessorNameCase($fullName)
{
	return mb_convert_case($fullName, MB_CASE_TITLE, "UTF-8");
}

function getSingleProfessor($id, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();
    $__cryptoKey = getCryptoKey();
    
    $query = "SELECT id,
    aes_decrypt(name, '$__cryptoKey') as name,
    aes_decrypt(email, '$__cryptoKey') as email,
    aes_decrypt(telephone, '$__cryptoKey') as telephone,
    aes_decrypt(schoolingLevel, '$__cryptoKey') as schoolingLevel,
    aes_decrypt(topicsOfInterest, '$__cryptoKey') as topicsOfInterest,
    aes_decrypt(lattesLink, '$__cryptoKey') as lattesLink,
    collectInss,
    aes_decrypt(personalDocsJson, '$__cryptoKey') as personalDocsJson,
    aes_decrypt(homeAddressJson, '$__cryptoKey') as homeAddressJson,
    aes_decrypt(miniResumeJson, '$__cryptoKey') as miniResumeJson,
    aes_decrypt(bankDataJson, '$__cryptoKey') as bankDataJson,
    agreesWithConsentForm,
    consentForm
    FROM professors WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRow;
}

function updateSingleProfessor($dbEntity, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();
    $__cryptoKey = getCryptoKey();

    $dbEntity->setCryptoKey($__cryptoKey);
    $dbEntity->name = formatProfessorNameCase($dbEntity->name);
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

function getUploadedPersonalDocs($professorId, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT * from professordocsattachments WHERE professorId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $professorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRows = $result->fetch_all(MYSQLI_ASSOC) ?? [];
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
    return $affectedRows > 0;
}

function insertNewProfessorWorkProposal($dbEntity, $filesPostData, $fileInputElementName, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    checkForUploadError($filesPostData[$fileInputElementName], $dbEntity->ownerProfessorId, 5242880, WORK_PROPOSAL_ALLOWED_TYPES);

    $dbEntity->fileExtension = pathinfo($filesPostData[$fileInputElementName]['name'], PATHINFO_EXTENSION);
    $dbEntity->registrationDate = date("Y-m-d H:i:s");
    $colsAndFields = $dbEntity->generateSQLCreateCommandColumnsAndFields();
    $query = "INSERT into professorworkproposals ($colsAndFields[columns]) VALUES ($colsAndFields[fields]) ";

    $stmt = $conn->prepare($query);
    $typesAndValues = $dbEntity->generateBindParamTypesAndValues();
    $stmt->bind_param($typesAndValues['types'], ...$typesAndValues['values']);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $newId = $conn->insert_id;
    $stmt->close();

    if (isId($newId))
        uploadWorkProposalFile($dbEntity->ownerProfessorId, $newId, $dbEntity->fileExtension, $filesPostData, $fileInputElementName);

    if (!$optConnection) $conn->close();
    return [ 'isCreated' => $affectedRows > 0, 'newId' => $newId ];
}

function getProfessorWorkProposal($workProposalId, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT * from professorworkproposals WHERE id = ? ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $workProposalId);
    $stmt->execute();
    $result = $stmt->get_result();
    $dataRow = $result->num_rows > 0 ? $result->fetch_row() : null;
    $stmt->close();
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRow;
}

function geWorkProposalsCount($searchKeywords, $professorId, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT count(professorworkproposals.id) FROM `professorworkproposals` 
    LEFT JOIN professorworksheets as ws on ws.professorWorkProposalId = professorworkproposals.id
    WHERE (professorworkproposals.ownerProfessorId = ? OR ws.professorId = ?) ";
    $bindParam = [ 'types' => "ii", 'values' => [$professorId, $professorId] ];

    if (mb_strlen($searchKeywords) > 3)
    {
        $query .= "AND MATCH (professorworkproposals.name, professorworkproposals.description) AGAINST (?) ";
        $bindParam['types'] .= "s";
        $bindParam['values'][] = $searchKeywords;
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param($bindParam['types'], ...$bindParam['values']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $count = $result->num_rows > 0 ? $result->fetch_row()[0] : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $count;
}

function getOwnedOrVinculatedWorkProposalsPartially($searchKeywords, $professorId, $page, $numResultsOnPage, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT professorworkproposals.* FROM `professorworkproposals` 
    LEFT JOIN professorworksheets as ws on ws.professorWorkProposalId = professorworkproposals.id
    WHERE (professorworkproposals.ownerProfessorId = ? OR ws.professorId = ?) ";
    $bindParam = [ 'types' => "ii", 'values' => [$professorId, $professorId] ];

    if (mb_strlen($searchKeywords) > 3)
    {
        $query .= "AND MATCH (professorworkproposals.name, professorworkproposals.description) AGAINST (?) ";
        $bindParam['types'] .= "s";
        $bindParam['values'][] = $searchKeywords;
    }

    if (isset($page, $numResultsOnPage))
    {
        $calc_page = ($page - 1) * $numResultsOnPage;
        $query .= "LIMIT ?, ?";
        $bindParam['types'] .= "ii";
        $bindParam['values'][] = $calc_page;
        $bindParam['values'][] = $numResultsOnPage;
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param($bindParam['types'], ...$bindParam['values']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRows = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRows;
}

function getSingleWorkProposal($professorId, $workProposalId, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT professorworkproposals.* FROM `professorworkproposals` 
    LEFT JOIN professorworksheets as ws on ws.professorWorkProposalId = professorworkproposals.id
    WHERE (professorworkproposals.ownerProfessorId = ? OR ws.professorId = ?) AND professorworkproposals.id = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iii', $professorId, $professorId, $workProposalId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRow;
}

function getWorkSheets($professorId, $workProposalId, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT * from professorworksheets WHERE professorId = ? AND professorWorkProposalId = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $professorId, $workProposalId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRows = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRows;

}