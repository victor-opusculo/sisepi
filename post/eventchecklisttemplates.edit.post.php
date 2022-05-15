<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/eventchecklists.database.php");
require_once("../model/DatabaseEntity.php");

if(isset($_POST["btnsubmitSubmitChecklistTemplate"]) && checkUserPermission("CHKLS", 1))
{
	$messages = [];
	try
	{
		$dbEntity = new DatabaseEntity('eventchecklisttemplate', $_POST);
		if(updateChecklistTemplate($dbEntity))
		{
			$messages[] = "Modelo de checklist modificado com sucesso!";
			writeLog("Modelo de checklist modificado. id: " . $_POST['jsontemplates:templateId']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao modificar modelo de checklist. " . $e->getMessage() . ". id: " . $_POST['jsontemplates:templateId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);