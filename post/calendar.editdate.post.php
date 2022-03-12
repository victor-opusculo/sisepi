<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/calendar.database.php");
require_once("../model/DatabaseEntity.php");

if(isset($_POST["btnsubmitSubmitDate"]) && checkUserPermission("CALEN", 4))
{
	$messages = [];
	try
	{
		$dbEntityFromPOST = new DatabaseEntity("calendardate", $_POST);

		$editEventResult = editCalendarEvent($dbEntityFromPOST);
		if($editEventResult)
		{
			$messages[] = "Data/evento editado com sucesso!";
			writeLog("Agenda: Data/evento editado. id: " . $_POST['calendarEventId']);
		}
		else
			throw new Exception("Erro: data/evento simples não criado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Agenda: Ao editar data/evento simples! " . $e->getMessage());
	}
	
	$messagesQuery = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET['id'], "messages=$messagesQuery"), true, 303);
}
else
	die(noPermissionMessage);