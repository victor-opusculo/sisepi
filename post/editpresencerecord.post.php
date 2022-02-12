<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/students.database.php");

if(isset($_POST["btnsubmitEditPresence"]) && checkUserPermission("EVENT", 7))
{
	$messages = [];
	if(editSinglePresenceRecordNoSubs($_POST))
	{
		$messages[] = "Dados alterados com sucesso!";
		writeLog("Presença editada. id: " . $_POST['presenceId']);
	}
	else
	{
		$messages[] = "Nenhum dado alterado.";
		writeLog("Presença não alterada. id: " . $_POST['presenceId']);
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$messagesString"), true, 303);
}
else
	die(noPermissionMessage);