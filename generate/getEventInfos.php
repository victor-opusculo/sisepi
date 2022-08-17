<?php
require_once("checkLogin.php");
require_once("../includes/Data/namespace.php");
require_once("../model/database/events.database.php");

header('Content-Type: application/json; charset=utf-8');

$eventId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : null;
$output = [];

$output['data'] = getEventBasicInfos2($eventId);

if (!is_null($output['data']))
	$output['data']['locTypes'] = Data\getEventMode($output['data']['locTypes']);

if (is_null($output['data'])) 
	$output['error'] = 'Evento não localizado.';

echo json_encode($output);
exit();