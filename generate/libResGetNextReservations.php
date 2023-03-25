<?php
require_once("../model/Database/ext.libraryreservations.database.php");

header('Content-Type: application/json; charset=utf-8');

$pubId = isset($_GET["pubId"]) && isId($_GET["pubId"]) ? $_GET["pubId"] : null;
$outputArray = null;

if ($pubId)
{
	$conn = createConnectionAsEditor();

	invalidatePendingAndOldReservations($pubId, $conn);
	$outputArray = getNextReservations($pubId, $conn);

	$conn->close();
	
	for ($i = 0; $i < count($outputArray); $i++)
		$outputArray[$i]["reservationDatetime"] = date_format(date_create($outputArray[$i]["reservationDatetime"]), "d/m/Y H:i:s");
}

$output = [ "reservations" => $outputArray ];

echo json_encode($output);
exit();