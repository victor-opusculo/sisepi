<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/students.database.php");

if(isset($_POST["btnsubmitDeletePresence"]) && checkUserPermission("EVENT", 5))
{
	$messages = [];
	if(deletePresenceRecord($_POST["presenceId"]))
	{
		$messages[] = "Presença excluída com sucesso!";
		writeLog("Presença excluída. id: " . $_POST["presenceId"]);
	}
	else
	{
		$messages[] = "Erro ao excluir presença.";
		writeErrorLog("Ao excluir presença. id: " . $_POST["presenceId"]);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);