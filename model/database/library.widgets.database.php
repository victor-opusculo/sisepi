<?php
require_once("database.php");

function getCollectionCount($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$count = 0;
	$query = "SELECT COUNT(id) FROM librarycollection";
	
	$result = $conn->query($query);
	if ($result->num_rows > 0)
		$count = $result->fetch_row()[0];
	$result->close();
	
	if (!$optConnection) $conn->close();
	return $count;
}

function getNonFinalizedLoansCount($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$count = 0;
	$query = "SELECT COUNT(id) FROM libraryborrowedpublications WHERE returnDatetime IS NULL";
	
	$result = $conn->query($query);
	if ($result->num_rows > 0)
		$count = $result->fetch_row()[0];
	$result->close();
	
	if (!$optConnection) $conn->close();
	return $count;
}

function getTotalLoansCount($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$count = 0;
	$query = "SELECT COUNT(id) FROM libraryborrowedpublications";
	
	$result = $conn->query($query);
	if ($result->num_rows > 0)
		$count = $result->fetch_row()[0];
	$result->close();
	
	if (!$optConnection) $conn->close();
	return $count;
}

function getNonFinalizedReservationsCount($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$count = 0;
	$query = "SELECT COUNT(id) FROM libraryreservations WHERE (borrowedPubId IS NULL AND invalidatedDatetime IS NULL)";
	
	$result = $conn->query($query);
	if ($result->num_rows > 0)
		$count = $result->fetch_row()[0];
	$result->close();
	
	if (!$optConnection) $conn->close();
	return $count;
}

function getTotalReservationsCount($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$count = 0;
	$query = "SELECT COUNT(id) FROM libraryreservations";
	
	$result = $conn->query($query);
	if ($result->num_rows > 0)
		$count = $result->fetch_row()[0];
	$result->close();
	
	if (!$optConnection) $conn->close();
	return $count;
}

function getTotalUsersCount($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$count = 0;
	$query = "SELECT COUNT(id) FROM libraryusers";
	
	$result = $conn->query($query);
	if ($result->num_rows > 0)
		$count = $result->fetch_row()[0];
	$result->close();
	
	if (!$optConnection) $conn->close();
	return $count;
}

function getNextDevolutions($optConnection = null)
{
	$conn = $optConnection ? $optConnection : createConnectionAsEditor();
	$query = "SELECT libraryborrowedpublications.id, publicationId, expectedReturnDatetime, librarycollection.title
FROM libraryborrowedpublications 
LEFT JOIN librarycollection ON librarycollection.id = libraryborrowedpublications.publicationId
WHERE (expectedReturnDatetime <= DATE_ADD(NOW(), INTERVAL 7 DAY) AND expectedReturnDatetime >= NOW() AND returnDatetime IS NULL) OR (expectedReturnDatetime < NOW() AND returnDatetime IS NULL)
ORDER BY expectedReturnDatetime ASC
LIMIT 10";
	$dataRows = null;
	$result = $conn->query($query);
	if ($result->num_rows > 0)
		$dataRows = $result->fetch_all(MYSQLI_ASSOC);
	$result->close();
	
	if (!$optConnection) $conn->close();
	return $dataRows;
}