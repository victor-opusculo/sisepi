<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/events.database.php");
require_once("../model/DatabaseEntity.php");

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("EVENT", 2))
{
	$messages = [];
	
	try
	{
		$dbEntities =
		[
			'main' => new DatabaseEntity("event", $_POST),
			'workPlan' => new DatabaseEntity("eventworkplan", $_POST)
		];

		if(updateFullEvent($dbEntities, $_POST, $_FILES))
		{
			$messages[] = "Evento alterado com sucesso!";
			writeLog("Evento alterado. id: " . $_POST['events:eventId']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (FileUploadException $e)
	{
		$messages[] = "Evento alterado, porém com erro.";
		$messages[] = $e->getMessage();
		writeErrorLog("Evento alterado com erro. " . $e->getMessage() . " id: " . $_POST['eventId']);
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeLog("Evento não alterado. id: " . $_POST['events:eventId']); 
	}
	
	$messagesQuery = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$messagesQuery"), true, 303);
}
else
	die(noPermissionMessage);