<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/calendar.database.php");

if(isset($_POST["btnsubmitDelete"]) && checkUserPermission("CALEN", 5))
{
	$messages = [];
	try
	{
		$deleteEventResult = deleteCalendarEvent($_POST['calendarEventId']);
		if($deleteEventResult)
		{
			$messages[] = "Data/evento excluído com sucesso!";
			writeLog("Agenda: Data/evento excluído. id: " . $_POST['calendarEventId']);
		}
		else
			throw new Exception("Erro: data/evento simples não excluído.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Agenda: Ao excluir data/evento simples! " . $e->getMessage());
	}
	
	$messagesQuery = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET['id'], "messages=$messagesQuery"), true, 303);
}
else
	die(noPermissionMessage);