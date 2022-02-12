<?php
require_once("../model/database/libraryborrowedpubs.database.php");

header('Content-Type: application/json; charset=utf-8');

$pubId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : null;
$output = [];

if ($pubId)
{
	try
	{
		$output = getSinglePublication($pubId);
	}
	catch (Exception $e)
	{
		$output["error"] = $e->getMessage();
	}
}

echo json_encode($output);
exit();