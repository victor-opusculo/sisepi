<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/mailing.database.php");

if(isset($_POST["btnsubmitDeleteEmail"]) && checkUserPermission("MAIL", 2))
{
	$messages = [];
	if(deleteEmail($_POST["mailId"]))
	{
		$messages[] = "E-mail excluído com sucesso!";
		writeLog("Mailing: E-mail excluído. id: " . $_POST["mailId"]);
	}
	else
	{
		$messages[] = "Erro: E-mail não excluído";
		writeErrorLog("Mailing: Ao excluir e-mail. id: " . $_POST["mailId"]);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);