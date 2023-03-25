<?php
require_once("checkLogin.php");
require_once("../model/Database/professors.database.php");

header('Content-Type: application/json; charset=utf-8');

$profId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : null;
$output = [];

$output['data'] = getSingleProfessor($profId);
if (is_null($output['data'])) 
	$output['error'] = 'Docente não localizado.';

echo json_encode($output);
exit();