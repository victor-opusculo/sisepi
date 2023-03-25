<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/libraryusers.database.php");

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("LIBR", 6))
{
	$messages = [];
	if(updateUser($_POST))
	{
		$messages[] = "Usuário alterado com sucesso!";
		writeLog("Biblioteca: Usuário alterado. id: " . $_POST['userId']);
	}
	else
	{
		$messages[] = "Nenhum dado alterado.";
		writeLog("Biblioteca: Usuário não alterado. id: " . $_POST['userId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);