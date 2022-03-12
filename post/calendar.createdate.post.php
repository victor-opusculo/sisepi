<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/calendar.database.php");
require_once("../model/DatabaseEntity.php");

if(isset($_POST["btnsubmitSubmitDate"]) && checkUserPermission("CALEN", 3))
{
	$messages = [];
	try
	{
		$dbEntityFromPOST = new DatabaseEntity("calendardate", $_POST);

		$createEventResult =  createCalendarEvent($dbEntityFromPOST);
		if($createEventResult['isCreated'])
		{
			$messages[] = "Data/evento criado com sucesso!";
			writeLog("Agenda: Data/evento criado. id: " . $createEventResult['newId']);
		}
		else
			throw new Exception("Erro: data/evento simples não criado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Agenda: Ao criar data/evento simples! " . $e->getMessage());
	}
	
	$messagesQuery = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$messagesQuery"), true, 303);
}
else
	die(noPermissionMessage);