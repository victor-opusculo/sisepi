<?php
require_once("../model/database/events.database.php");

header('Content-Type: application/json; charset=utf-8');

$eventId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : null;
$output = [];

if ($eventId)
{
	$output = (object)getSingleEvent($eventId);
}

echo json_encode($output);
exit();