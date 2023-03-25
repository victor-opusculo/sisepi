<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once "../vendor/autoload.php";

use \SisEpi\Model\Calendar\CalendarDate;
use \SisEpi\Model\Database\Connection;

if(isset($_POST["btnsubmitDelete"]) && checkUserPermission("CALEN", 5))
{
	$messages = [];
	$conn = Connection::create();
	try
	{
		$date = new CalendarDate();
		$date->fillPropertiesFromFormInput($_POST);
		$deleteEventResult = $date->delete($conn);
		//$deleteEventResult = deleteCalendarEvent($_POST['calendarEventId']);
		if($deleteEventResult['affectedRows'] > 0)
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
	finally { $conn->close(); }
	
	$messagesQuery = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$messagesQuery"), true, 303);
}
else
	die(noPermissionMessage);