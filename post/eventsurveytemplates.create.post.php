<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/eventchecklists.database.php");
require_once("../model/DatabaseEntity.php");

if(isset($_POST["btnsubmitSubmitSurveyTemplate"]) && checkUserPermission("SRVEY", 1))
{
	$messages = [];
	try
	{
		$dbEntity = new DatabaseEntity('eventsurveytemplate', $_POST);
		$createInfos = createChecklistTemplate($dbEntity);
		if($createInfos['isCreated'])
		{
			$messages[] = "Modelo de pesquisa de satisfação criado com sucesso!";
			writeLog("Modelo de pesquisa de satisfação criado. id: " . $createInfos['newId']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao criar modelo de pesquisa de satisfação. " . $e->getMessage());
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);