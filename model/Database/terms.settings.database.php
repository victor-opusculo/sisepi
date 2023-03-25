<?php
require_once "database.php";
require_once "terms.uploadFiles.php";

function buildTermsQuery($baseQuery, $searchKeywords, $_orderBy, $_limit)
{
    $output = [ 'query' => null, 'bindParamTypes' => "", 'bindParamValues' => [] ];

    $where = "";
    if (mb_strlen($searchKeywords) > 3)
    {
        $where = "WHERE MATCH(terms.name) AGAINST (?) ";
        $output['bindParamTypes'] .= "s";
        $output['bindParamValues'][] = $searchKeywords;
    }

    $orderBy = "ORDER BY ";
    switch ($_orderBy)
    {
        case 'name': $orderBy .= "terms.name ASC "; break;
        case 'registrationDate': $orderBy .= "terms.registrationDate ASC "; break;
        case 'id': default: $orderBy .= "terms.id ASC "; break;
    }

    $limit = "";
    if (is_array($_limit))
    {
        $limit = "LIMIT ?, ? ";
        $output['bindParamTypes'] .= "ii";
        $output['bindParamValues'][] = $_limit[0];
        $output['bindParamValues'][] = $_limit[1];
    }

    $output['query'] = $baseQuery . " " . $where . $orderBy . $limit;
    return $output;
}

function getTermsCount($searchKeywords, ?mysqli $optConnection = null)
{
    $conn = $optConnection ?? createConnectionAsEditor();

    $baseQuery = "SELECT COUNT(*) FROM terms ";
    $queryBuilt = buildTermsQuery($baseQuery, $searchKeywords, null, null);
    $stmt = $conn->prepare($queryBuilt['query']);
    if (!empty($queryBuilt['bindParamValues']))
        $stmt->bind_param($queryBuilt['bindParamTypes'], ...$queryBuilt['bindParamValues']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $count = $result->num_rows > 0 ? $result->fetch_row()[0] : 0;
    $result->close();

    if (!$optConnection) $conn->close();
    return $count;
}

function getTermsPartially($page, $numResultsOnPage, $searchKeywords, $orderBy, ?mysqli $optConnection = null)
{
    $conn = $optConnection ?? createConnectionAsEditor();

    $baseQuery = "SELECT * FROM terms ";
	$calc_page = ($page - 1) * $numResultsOnPage;
    $queryBuilt = buildTermsQuery($baseQuery, $searchKeywords, $orderBy, [ $calc_page, $numResultsOnPage ]);
    $stmt = $conn->prepare($queryBuilt['query']);
    if (!empty($queryBuilt['bindParamValues']))
        $stmt->bind_param($queryBuilt['bindParamTypes'], ...$queryBuilt['bindParamValues']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $dataRows = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRows;
}

function getSingleTerm($id, ?mysqli $optConnection = null)
{
    $conn = $optConnection ?? createConnectionAsEditor();

    $query = "SELECT * FROM terms WHERE id = ? ";
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

function createTerm($dbEntity, $filesPostData, ?mysqli $optConnection = null)
{
    $conn = $optConnection ?? createConnectionAsEditor();

    $dbEntity->registrationDate = date('Y-m-d H:i:s');

    checkForTermUploadError($filesPostData["fileTermPdf"], null, 5242880 /* 5MB */);

    $colsAndFields = $dbEntity->generateSQLCreateCommandColumnsAndFields();
    $query = "INSERT INTO terms ($colsAndFields[columns]) VALUES ($colsAndFields[fields]) ";

    $stmt = $conn->prepare($query);
    $typesAndValues = $dbEntity->generateBindParamTypesAndValues();
    $stmt->bind_param($typesAndValues['types'], ...$typesAndValues['values']);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $newId = $conn->insert_id;
    $stmt->close();

    uploadTermFile($newId, $filesPostData, "fileTermPdf");

    if (!$optConnection) $conn->close();
    return [ 'isCreated' => $affectedRows > 0, 'newId' => $newId ];
}

function updateTerm($dbEntity, $filesPostData, ?mysqli $optConnection = null)
{
    $conn = $optConnection ?? createConnectionAsEditor();

    if (isset($filesPostData["fileTermPdf"]) && is_uploaded_file($filesPostData["fileTermPdf"]['tmp_name']))
    {
        checkForTermUploadError($filesPostData["fileTermPdf"], null, 5242880 /* 5MB */);
        $dbEntity->registrationDate = date('Y-m-d H:i:s');
    }

    $colsAndFields = $dbEntity->generateSQLUpdateCommandColumnsAndFields();
    $query = "UPDATE terms SET $colsAndFields[setColumnsAndFields] WHERE $colsAndFields[whereCondition] ";

    $stmt = $conn->prepare($query);
    $typesAndValues = $dbEntity->generateBindParamTypesAndValues();
    $stmt->bind_param($typesAndValues['types'], ...$typesAndValues['values']);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    if (isset($filesPostData["fileTermPdf"]) && is_uploaded_file($filesPostData["fileTermPdf"]['tmp_name']))
    {
        deleteTermFile($dbEntity->id);
        uploadTermFile($dbEntity->id, $filesPostData, "fileTermPdf");
    }

    if (!$optConnection) $conn->close();
    return $affectedRows > 0;
}

function deleteTerm($id, ?mysqli $optConnection = null)
{
    $conn = $optConnection ?? createConnectionAsEditor();

    $query = "DELETE FROM terms WHERE id = ? ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    if ($affectedRows > 0)
        deleteTermFile($id);

    if (!$optConnection) $conn->close();
    return $affectedRows;
}