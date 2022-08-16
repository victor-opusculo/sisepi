<?php
require_once("database.php");
require_once("professors.uploadFiles.php");

if (!function_exists('formatProfessorNameCase'))
{
    function formatProfessorNameCase($fullName)
    {
        return mb_convert_case($fullName, MB_CASE_TITLE, "UTF-8");
    }
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

    $query = "SELECT DISTINCT professorworkproposals.* FROM `professorworkproposals` 
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

function checkForWorkProposalOwnership($professorId, $workProposalId, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT professorworkproposals.* FROM `professorworkproposals` 
    WHERE professorworkproposals.ownerProfessorId = ? AND professorworkproposals.id = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $professorId, $workProposalId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $found = $result->num_rows > 0;
    $result->close();

    if (!$optConnection) $conn->close();
    return $found;
}

function getWorkSheets($professorId, $workProposalId, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT professorworksheets.*, events.name as 'eventName' from professorworksheets 
    LEFT JOIN events ON events.id = professorworksheets.eventId 
    WHERE professorId = ? AND professorWorkProposalId = ? ";
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

function getSingleWorkSheet($professorId, $workSheetId, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT * from professorworksheets 
    WHERE professorId = ? AND id = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $professorId, $workSheetId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRow;
}

function updateWorkProposal($dbEntity, $filesPostData, $fileInputElementName, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $affectedRows = 0;
    if (is_uploaded_file($filesPostData[$fileInputElementName]['tmp_name']))
    {
        checkForUploadError($filesPostData[$fileInputElementName], $dbEntity->ownerProfessorId, 5242880, WORK_PROPOSAL_ALLOWED_TYPES);
        $dbEntity->fileExtension = pathinfo($filesPostData[$fileInputElementName]['name'], PATHINFO_EXTENSION);

        if (!deleteWorkProposalFile($dbEntity->id)) throw new Exception("Não foi possível excluir o arquivo da proposta antigo.");
        if (!uploadWorkProposalFile($dbEntity->ownerProfessorId, $dbEntity->id, $dbEntity->fileExtension, $filesPostData, $fileInputElementName))
                throw new Exception("Não foi possível subir o novo arquivo da proposta.");

        $affectedRows++;
    }

    $dbEntity->registrationDate = date("Y-m-d H:i:s");
    $colsAndFields = $dbEntity->generateSQLUpdateCommandColumnsAndFields();
    $query = "UPDATE professorworkproposals SET $colsAndFields[setColumnsAndFields] WHERE $colsAndFields[whereCondition] ";

    $stmt = $conn->prepare($query);
    $typesAndValues = $dbEntity->generateBindParamTypesAndValues();
    $stmt->bind_param($typesAndValues['types'], ...$typesAndValues['values']);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    if (!$optConnection) $conn->close();
    return $affectedRows > 0;
}

function getSingleEvent($id, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT * from events 
    WHERE id = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRow;
}

function getSingleDocTemplate($id, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT * from jsontemplates 
    WHERE type = 'professorworkdoc' AND id = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRow;
}

function isProfessorCertificateAlreadyIssued(int $workSheetId, ?mysqli $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $stmt = $conn->prepare("SELECT id, dateTime FROM professorcertificates WHERE workSheetId = ? ");
    $stmt->bind_param('i', $workSheetId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRow;
}

function saveProfessorCertificateInfos(int $workSheetId, string $issueDateTime, ?mysqli $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $stmt = $conn->prepare("INSERT INTO professorcertificates (workSheetId, dateTime) VALUES (?, ?) ");
    $stmt->bind_param('is', $workSheetId, $issueDateTime);
    $stmt->execute();
    $newId = $conn->insert_id;
    $stmt->close();

    if (!$newId) throw new Exception('Não foi possível salvar o registro de certificado de docente gerado.');

    if (!$optConnection) $conn->close();
    return $newId;
}

function getWorkDocSignatures(int $workSheetId, int $professorId, ?mysqli $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();
    $stmt = $conn->prepare("SELECT id, workSheetId, docSignatureId, professorId, signatureDateTime 
    FROM professorworkdocsignatures 
    WHERE workSheetId = ? AND professorId = ? ");
    $stmt->bind_param('ii', $workSheetId, $professorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRows = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRows;
}

function insertWorkDocSignature(int $workSheetId, int $professorId, array $signatureFieldsIds, ?mysqli $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();
    $affectedRows = 0;

    $stmt = $conn->prepare("SELECT (NOW() >= signatureDate) AS canSign FROM professorworksheets Where id = ? ");
    $stmt->bind_param('i', $workSheetId);
    $stmt->execute();
    $canSign = $stmt->get_result()->fetch_row()[0];
    $stmt->close();

    if (!(bool)$canSign)
        throw new Exception('Tentativa de assinar documentação antes da data.');

    $stmt = $conn->prepare("INSERT INTO professorworkdocsignatures (workSheetId, docSignatureId, professorId, signatureDateTime)
        VALUES (?, ?, ?, NOW())");

    foreach ($signatureFieldsIds as $fid)
    {
        if (verifyIfWorkDocIsAlreadySigned($workSheetId, $professorId, $fid, $conn))
            continue;

        $stmt->bind_param('iii', $workSheetId, $fid, $professorId);
        $stmt->execute();
        $affectedRows += $stmt->affected_rows;
    }
    $stmt->close();

    if (!$optConnection) $conn->close();
    return $affectedRows > 0;
}

function verifyIfWorkDocIsAlreadySigned(int $workSheetId, int $professorId, int $signatureFieldId, ?mysqli $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();
    $stmt = $conn->prepare("SELECT count(*) FROM professorworkdocsignatures WHERE workSheetId = ? AND docSignatureId = ? AND professorId = ?");
    $stmt->bind_param('iii', $workSheetId, $signatureFieldId, $professorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $exists = $result->fetch_row()[0] > 0;
    $result->close();
    if (!$optConnection) $conn->close();
    return $exists;
}

function updateInssDeclaration(int $workSheetId, array $companiesArray, ?mysqli $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query1 = "UPDATE professorworksheets SET paymentInfosJson = JSON_SET(paymentInfosJson, '$.companies', JSON_EXTRACT(?, '$')) WHERE id = ? ";
    $stmt = $conn->prepare($query1);
    $jsonComp = json_encode($companiesArray);
    $stmt->bind_param('si', $jsonComp, $workSheetId);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    if (!$optConnection) $conn->close();
    return $affectedRows > 0;
}