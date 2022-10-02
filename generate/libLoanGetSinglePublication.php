<?php
require_once("../model/database/libraryborrowedpubs.database.php");
require_once "../model/librarycollection/Publication.php";

header('Content-Type: application/json; charset=utf-8');

$pubId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : null;
$output = [];

if ($pubId)
{
	$conn = createConnectionAsEditor();
	try
	{
		$getter = new Model\LibraryCollection\Publication();
		$getter->id = $pubId;
		$pubObj = $getter->getSingle($conn);
		$isAvailable = $getter->isAvailableForBorrowing($conn);

		$output['data'] =
		[
			'id' => $pubObj->id,
			'title' => $pubObj->title,
			'author' => $pubObj->author,
			'publisher' => $pubObj->publisher_edition,
			'volume' => $pubObj->volume,
			'copyNumber' => $pubObj->copyNumber,
			'isAvailable' => $isAvailable
		];
	}
	catch (Exception $e)
	{
		$output["error"] = $e->getMessage();
	}
	finally
	{
		$conn->close();
	}
}

echo json_encode($output);
exit();