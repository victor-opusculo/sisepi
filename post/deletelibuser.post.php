<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/libraryusers.database.php");

if(isset($_POST["btnsubmitDeleteUser"]) && checkUserPermission("LIBR", 7))
{
	$messages = [];
	if(deleteUser($_POST["userId"]))
	{
		$messages[] = "Usuário excluído com sucesso!";
		writeLog("Biblioteca: Usuário excluído. id: " . $_POST["userId"]);
	}
	else
	{
		$messages[] = "Erro: Usuário não excluído.";
		writeErrorLog("Biblioteca: Ao excluir usuário. id: " . $_POST["userId"]);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);