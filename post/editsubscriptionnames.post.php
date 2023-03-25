<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/students.database.php");

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("EVENT", 7))
{
	$messages = [];
	if(updateSubscription($_POST))
	{
		$messages[] = "Dados alterados com sucesso!";
		writeLog("Dados de inscrição alterados. id: " . $_POST['subscriptionId']);
	}
	else
	{
		$messages[] = "Dados não alterados.";
		writeLog("Dados de inscrição não alterados. id: " . $_POST['subscriptionId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);