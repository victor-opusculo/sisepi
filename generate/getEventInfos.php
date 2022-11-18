<?php
require_once("checkLogin.php");
require_once("../includes/Data/namespace.php");
require_once("../model/database/events.database.php");
require_once "../model/events/Event.php";

header('Content-Type: application/json; charset=utf-8');

$eventId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : null;
$output = [];

$getter = new \Model\Events\Event();
$getter->id = $eventId;

$conn = createConnectionAsEditor();
try
{
	$output['data'] = $getter->getSingleDataRow($conn);
	$output['data']['locTypes'] = Data\getEventMode($output['data']['locTypes']);
}
catch (Exception $e)
{
	$output['error'] = $e->getMessage();
}
finally { $conn->close(); }

echo json_encode($output);
exit();