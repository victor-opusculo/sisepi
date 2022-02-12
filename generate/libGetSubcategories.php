<?php
require_once("../model/database/librarycollection.database.php");

header('Content-Type: application/json; charset=utf-8');

$catId = isset($_GET["catId"]) && isId($_GET["catId"]) ? $_GET["catId"] : null;
$output = [];

if ($catId)
{
	$output["subcategories"] = [];
	$subcategories = getSubcategories($catId);
	foreach ($subcategories as $sc)
		$output["subcategories"][] = [ "id" => $sc["id"], "name" => $sc["name"] ];
}

echo json_encode($output);
exit();