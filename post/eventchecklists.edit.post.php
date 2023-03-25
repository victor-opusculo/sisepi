<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/eventchecklists.database.php");
require_once("../model/DatabaseEntity.php");

if(isset($_POST["btnsubmitSubmitEventChecklist"]) && checkUserPermission("CHKLS", 1))
{
	$messages = [];
	try
	{
		$dbEntity = new DatabaseEntity('eventchecklists', $_POST);
		if(updateSingleChecklist($dbEntity) > 0)
		{
			$messages[] = "Checklist modificado com sucesso!";
			writeLog("Checklist modificado. id: " . $_POST['eventchecklists:checklistId']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao modificar checklist. " . $e->getMessage() . ". id: " . $_POST['eventchecklists:checklistId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);