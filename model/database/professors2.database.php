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