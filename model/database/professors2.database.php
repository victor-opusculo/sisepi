<?php
require_once ("database.php");
require_once ("professors.uploadFiles.php");

function buildWorkProposalsQuery($queryBase, $searchKeywords, $orderByParam = null, $limit = null)
{
	$outputInfos = [ 'query' => null, 'types' => "", 'values' => [] ];

	$where = "";
	if (mb_strlen($searchKeywords) > 3)
	{
		$where = "WHERE MATCH (professorworkproposals.name, professorworkproposals.description) AGAINST (?) ";
		$outputInfos['types'] .= 's';
		$outputInfos['values'][] = $searchKeywords;
	}

	$orderBy = "";
	switch ($orderByParam)
	{
        case 'approved':
			$orderBy = "ORDER BY professorworkproposals.isApproved ASC "; break;
		case 'date':
			$orderBy = "ORDER BY professorworkproposals.registrationDate DESC "; break;
		case 'name':
		default:
			$orderBy = "ORDER BY professorworkproposals.name ASC "; break;
	}

	$limitC = "";
	if (isset($limit) && is_array($limit))
	{
		$limitC = "LIMIT ?, ?";
		$outputInfos['types'] .= "ii";
		$outputInfos['values'][] = $limit[0];
		$outputInfos['values'][] = $limit[1];
	}

	$outputInfos['query'] = $queryBase . $where . $orderBy . $limitC;
	return $outputInfos;
}

function getWorkProposalsCount($searchKeywords, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "SELECT count(*) FROM professorworkproposals ";
	$infos = buildWorkProposalsQuery($query, $searchKeywords, null, null);
	$stmt = $conn->prepare($infos['query']);
	if (!empty($infos['types'])) $stmt->bind_param($infos['types'], ...$infos['values']);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$count = $result->num_rows > 0 ? $result->fetch_row()[0] : 0;
	$result->close();

    if (!$optConnection) $conn->close();
	return $count;
}

function getWorkProposalsPartially($searchKeywords, $orderBy, $page, $numResultsOnPage, $optConnection = null)
{
	$__cryptoKey = getCryptoKey();
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$query = "SELECT professorworkproposals.id, professorworkproposals.name, professorworkproposals.isApproved, professorworkproposals.registrationDate,
	aes_decrypt(professors.name, '$__cryptoKey') as ownerProfessorName 
	FROM professorworkproposals 
	LEFT JOIN professors ON professors.id = professorworkproposals.ownerProfessorId ";
	$infos = buildWorkProposalsQuery($query, $searchKeywords, $orderBy, [ ($page - 1) * $numResultsOnPage, $numResultsOnPage ]);
	$stmt = $conn->prepare($infos['query']);
	if (!empty($infos['types']))  $stmt->bind_param($infos['types'], ...$infos['values']);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$dataRows = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
	$result->close();

    if (!$optConnection) $conn->close();
	return $dataRows;
}

function getSingleWorkProposal($workProposalId, $optConnection = null)
{
    $__cryptoKey = getCryptoKey();
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT professorworkproposals.*,
	aes_decrypt(professors.name, '$__cryptoKey') as ownerProfessorName 
	FROM professorworkproposals 
	LEFT JOIN professors ON professors.id = professorworkproposals.ownerProfessorId 
    WHERE professorworkproposals.id = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $workProposalId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRow;
}

function setWorkProposalStatus($workProposalId, $approvedStatus, $feedbackMessage, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "UPDATE professorworkproposals SET isApproved = ?, feedbackMessage = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $approvedStatus, $feedbackMessage, $workProposalId);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    if (!$optConnection) $conn->close();
    return $affectedRows > 0;
}

function getProfessorsList(?mysqli $optConnection = null)
{
    $__cryptoKey = getCryptoKey();
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $result = $conn->query("SELECT id, aes_decrypt(name, '$__cryptoKey') as name FROM professors ");
    $dataRows = $result->fetch_all(MYSQLI_ASSOC);

    if (!$optConnection) $conn->close();
    return $dataRows;
}

function updateSingleWorkProposal($dbEntity, $filesPostData, $fileInputElementName, $optConnection = null)
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

function insertNewWorkProposal($dbEntity, $filesPostData, $fileInputElementName, $optConnection = null)
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

function deleteWorkProposal($workProposalId, bool $deleteAssociatedWorkSheets, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "DELETE from professorworkproposals WHERE id = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $workProposalId);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    if ($affectedRows > 0) deleteWorkProposalFile($workProposalId);

    if ($affectedRows > 0 && $deleteAssociatedWorkSheets)
    {
        $query = "DELETE FROM professorworksheets WHERE professorWorkProposalId = ? ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $workProposalId);
        $stmt->execute();
        $affectedRows += $stmt->affected_rows;
        $stmt->close();
    }

    if (!$optConnection) $conn->close();
    return $affectedRows > 0;
}

function getWorkSheets(int $workProposalId, ?mysqli $optConnection = null)
{
    $__cryptoKey = getCryptoKey();
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT pws.id, pws.professorId, pws.eventId, pws.signatureDate, events.name as eventName, aes_decrypt(prof.name, '$__cryptoKey') as professorName FROM professorworksheets as pws
    LEFT JOIN professors as prof ON prof.id = pws.professorId
    LEFT JOIN events ON events.id = pws.eventId
    WHERE pws.professorWorkProposalId = ?
    ORDER BY pws.id ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $workProposalId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRows = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRows;
}

function getSingleWorkSheet(int $workSheetId, ?mysqli $optConnection = null)
{
    $__cryptoKey = getCryptoKey();
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $query = "SELECT professorworksheets.*, events.name as eventName, aes_decrypt(professors.name, '$__cryptoKey') as professorName, jsontemplates.name as 'docTemplateName' FROM professorworksheets 
    LEFT JOIN events ON events.id = professorworksheets.eventId
    LEFT JOIN professors ON professors.id = professorworksheets.professorId
    LEFT JOIN jsontemplates ON jsontemplates.type = 'professorworkdoc' AND jsontemplates.id = professorworksheets.professorDocTemplateId 
    WHERE professorworksheets.id = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $workSheetId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRow;
}

function insertWorkSheet($dbEntity, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $stmt = $conn->prepare('SELECT * from professors where id = ? ');
    $stmt->bind_param('i', $dbEntity->professorId);
    $stmt->execute();
    $result1 = $stmt->get_result();
    $stmt->close();
    if ($result1->num_rows < 1)
    {
        $result1->close(); if (!$optConnection) $conn->close();
        throw new Exception('O docente definido para esta ficha não existe.');
    }
    else
        $result1->close();

    $colsAndFields = $dbEntity->generateSQLCreateCommandColumnsAndFields();
    $query = "INSERT into professorworksheets ($colsAndFields[columns]) VALUES ($colsAndFields[fields]) ";

    $stmt = $conn->prepare($query);
    $typesAndValues = $dbEntity->generateBindParamTypesAndValues();
    $stmt->bind_param($typesAndValues['types'], ...$typesAndValues['values']);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $newId = $conn->insert_id;
    $stmt->close();

    if (!$optConnection) $conn->close();
    return [ 'isCreated' => $affectedRows > 0, 'newId' => $newId ];
}

function updateWorkSheet($dbEntity, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $stmt = $conn->prepare('SELECT * from professors where id = ? ');
    $stmt->bind_param('i', $dbEntity->professorId);
    $stmt->execute();
    $result1 = $stmt->get_result();
    $stmt->close();
    if ($result1->num_rows < 1)
    {
        $result1->close(); if (!$optConnection) $conn->close();
        throw new Exception('O docente definido para esta ficha não existe.');
    }
    else
        $result1->close();

    $colsAndFields = $dbEntity->generateSQLUpdateCommandColumnsAndFields();
    $query = "UPDATE professorworksheets SET $colsAndFields[setColumnsAndFields] WHERE $colsAndFields[whereCondition] ";
    $stmt = $conn->prepare($query);
    $typesAndValues = $dbEntity->generateBindParamTypesAndValues();
    $stmt->bind_param($typesAndValues['types'], ...$typesAndValues['values']);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    if ($affectedRows > 0) invalidateWorkDocSignatures($dbEntity->id, $conn);

    if (!$optConnection) $conn->close();
    return $affectedRows > 0; 
}

function deleteWorkSheet(int $id, ?mysqli $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $stmt = $conn->prepare("DELETE from professorworksheets WHERE id = ? ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    if (!$optConnection) $conn->close();
    return $affectedRows > 0; 
}

function getDocTemplates(?mysqli $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $result = $conn->query("SELECT id, name FROM jsontemplates WHERE type = 'professorworkdoc' ");
    $dataRows = $result->fetch_all(MYSQLI_ASSOC);

    if (!$optConnection) $conn->close();
    return $dataRows;
}

function getSingleDocTemplate($templateId, ?mysqli $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $stmt = $conn->prepare("SELECT id, name, templateJson FROM jsontemplates WHERE type = 'professorworkdoc' AND id = ? ");
    $stmt->bind_param('i', $templateId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRow;
}

function getSingleEvent($id, ?mysqli $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ? ");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRow = $result->num_rows > 0 ? $result->fetch_assoc() : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRow;
}

function isProfessorRegistrationComplete($professorId, ?mysqli $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $stmt = $conn->prepare("SELECT collectInss, inssCollectInfos, personalDocsJson, homeAddressJson, miniResumeJson, bankDataJson FROM professors WHERE id = ? ");
    $stmt->bind_param('i', $professorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRow = $result->num_rows > 0 ? $result->fetch_row() : [ null ];
    $result->close();

    if (!$optConnection) $conn->close();
    return array_reduce($dataRow, fn($carry, $item) => isset($item) && $carry, true);
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

    return $dataRows;
}

function invalidateWorkDocSignatures(int $workSheetId, ?mysqli $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();
    $stmt = $conn->prepare("DELETE FROM professorworkdocsignatures WHERE workSheetId = ?");
    $stmt->bind_param('i', $workSheetId);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $stmt->close();
    return $affectedRows > 0;
}