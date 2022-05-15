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
		$createInfos = createChecklistTemplate($dbEntity);
		if($createInfos['isCreated'])
		{
			$messages[] = "Modelo de checklist criado com sucesso!";
			writeLog("Modelo de checklist criado. id: " . $createInfos['newId']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao criar modelo de checklist. " . $e->getMessage());
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);