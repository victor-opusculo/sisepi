<?php
require_once("../model/database/libraryborrowedpubs.database.php");

header('Content-Type: application/json; charset=utf-8');

$userId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : null;
$output = [];

if ($userId)
{
	try
	{
		$output = getSingleUser($userId);
	}
	catch (Exception $e)
	{
		$output["error"] = $e->getMessage();
	}
}

echo json_encode($output);
exit();