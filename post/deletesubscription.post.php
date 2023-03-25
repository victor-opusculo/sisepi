<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/students.database.php");

if(isset($_POST["btnsubmitDeleteSubscription"]) && checkUserPermission("EVENT", 6))
{
	$messages = [];
	if(deleteSubscription($_POST["subsId"]))
	{
		$messages[] = "Inscrição excluída com sucesso!";
		writeLog("Inscrição excluída. id: " . $_POST["subsId"]);
	}
	else
	{
		$messages[] = "Erro: Inscrição não excluída.";
		writeErrorLog("Inscrição não excluída. id: " . $_POST["subsId"]);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);