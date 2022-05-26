<?php
//private
require_once("database.php");

function getAllLocations($optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $result = $conn->query("SELECT * FROM eventlocations");
    $dataRows = $result->fetch_all(MYSQLI_ASSOC) ?? null;
    $result->close();

    if (!$optConnection) $conn->close();
    return $dataRows;
}

function createLocation($dbEntity, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$insertedId = null;
	$affectedRows = 0;

	$dbEntityInfos = $dbEntity->generateSQLCreateCommandColumnsAndFields();
	$query = "insert into eventlocations ($dbEntityInfos[columns]) values ($dbEntityInfos[fields])";

	$stmt = $conn->prepare($query);
	$bindParamInfos = $dbEntity->generateBindParamTypesAndValues();
	$stmt->bind_param($bindParamInfos['types'], ...$bindParamInfos['values']); 
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();
		
	$insertedId = $conn->insert_id;

	if (!$optConnection) $conn->close();
	return [ 'affectedRows' => $affectedRows, 'newId' => $insertedId ];
}

function updateLocation($dbEntity, $optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();

	$affectedRows = 0;
	$dbEntityInfos = $dbEntity->generateSQLUpdateCommandColumnsAndFields();
	$query = "update eventlocations set $dbEntityInfos[setColumnsAndFields] where $dbEntityInfos[whereCondition]";

	$stmt = $conn->prepare($query);
	$bindParamInfos = $dbEntity->generateBindParamTypesAndValues();
	$stmt->bind_param($bindParamInfos['types'], ...$bindParamInfos['values']);
	$stmt->execute();
	$affectedRows = $stmt->affected_rows;
	$stmt->close();

	if (!$optConnection) $conn->close();
	return $affectedRows;
}

function deleteLocation($dbEntity, $optConnection = null)
{
    $conn = $optConnection ? $optConnection : createConnectionAsEditor();

    $affectedRows = 0;
    $query = "delete from eventlocations where " . $dbEntity->generateSQLDeleteCommandWhereClause();
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $affectedRows = $stmt->affected_rows;
    $stmt->close();

    if (!$optConnection) $conn->close();
    return $affectedRows;
}

function executeDbEntitiesChangesReport($dbEntitiesChangesReport)
{
    $conn = createConnectionAsEditor();

    $affectedRows = 0;
    foreach ($dbEntitiesChangesReport['create'] as $dbeCreate)
        $affectedRows += createLocation($dbeCreate, $conn)['affectedRows'];

    foreach ($dbEntitiesChangesReport['update'] as $dbeUpdate)
        $affectedRows += updateLocation($dbeUpdate, $conn);

    foreach ($dbEntitiesChangesReport['delete'] as $dbeDelete)
        $affectedRows += deleteLocation($dbeDelete, $conn);

    $conn->close();

    return $affectedRows;
}