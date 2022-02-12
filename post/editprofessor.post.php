<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/professors.database.php");

if(isset($_POST["btnsubmitEditProfessor"]) && checkUserPermission("PROFE", 2))
{
	$messages = [];
	if(updateProfessor($_POST))
	{
		$messages[] = "Docente atualizado com sucesso!";
		writeLog("Docente alterado. id: " . $_POST['profId']);
	}
	else
	{
		$messages[] = "Nenhum dado alterado.";
		writeLog("Docente não alterado. id: " . $_POST['profId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);