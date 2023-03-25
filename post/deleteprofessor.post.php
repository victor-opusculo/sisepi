<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/professors.database.php");

if(isset($_POST["btnsubmitDeleteProfessor"]) && checkUserPermission("PROFE", 3))
{
	$messages = [];
	if(deleteProfessor($_POST["profId"]))
	{
		$messages[] = "Docente excluído com sucesso!";
		writeLog("Docente excluído. id: " . $_POST["profId"]);
	}
	else
	{
		$messages[] = "Erro: Docente não excluído.";
		writeErrorLog("Ao excluir docente. id: " . $_POST["profId"]);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);