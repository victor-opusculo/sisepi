<?php
require_once("checkLogin.php");
require_once("../model/database/events.database.php");

header('Content-Type: application/json; charset=utf-8');

$eventId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : null;
$output = [];

$output['data'] = getSingleEvent($eventId);
if (is_null($output['data'])) 
	$output['error'] = 'Evento não localizado.';

echo json_encode($output);
exit();