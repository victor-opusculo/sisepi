<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/professors.database.php");
require_once("../model/DatabaseEntity.php");

if(isset($_POST["btnsubmitProfessorEditPersonalInfos"]) && checkUserPermission("PROFE", 2))
{
	$messages = [];
	$dbEntity = new DatabaseEntity('Professor', $_POST);
	if(updateProfessor($dbEntity))
	{
		$messages[] = "Docente atualizado com sucesso!";
		writeLog("Docente alterado. id: " . $_POST['professors:profId']);
	}
	else
	{
		$messages[] = "Nenhum dado alterado.";
		writeLog("Docente não alterado. id: " . $_POST['professors:profId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);