<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/events.database.php");

if(isset($_POST["btnsubmitDeleteEvent"]) && checkUserPermission("EVENT", 3))
{
	$messages = [];
	if(deleteFullEvent($_POST["eventId"]))
	{
		$messages[] = "Evento excluído com sucesso!";
		writeLog("Evento excluído. id: " . $_POST['eventId']);
	}
	else
	{
		$messages[] = "Erro: Evento não excluído.";
		writeErrorLog("Ao excluir evento. id: " . $_POST['eventId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);