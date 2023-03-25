<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/eventchecklists.database.php");

if(isset($_POST["btnsubmitDelete"]) && checkUserPermission("CHKLS", 2))
{
	$messages = [];
	try
	{
		if(deleteChecklistTemplate($_POST['checklistTemplateId']))
		{
			$messages[] = "Modelo de checklist excluído com sucesso!";
			writeLog("Modelo de checklist excluído. id: " . $_POST['checklistTemplateId']);
		}
		else
			throw new Exception("Modelo de checklist não excluído.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir modelo de checklist. " . $e->getMessage() . ". id: " . $_POST['checklistTemplateId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);