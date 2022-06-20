<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/eventsurveys.database.php");
require_once("../model/DatabaseEntity.php");

if(isset($_POST["btnsubmitSubmitSurveyTemplate"]) && checkUserPermission("SRVEY", 2))
{
	$messages = [];
	try
	{
		$dbEntity = new DatabaseEntity('eventsurveytemplate', $_POST);
		if(updateSingleSurveyTemplate($dbEntity))
		{
			$messages[] = "Modelo de pesquisa de satisfação modificado com sucesso!";
			writeLog("Modelo de pesquisa de satisfação modificado. id: " . $_POST['jsontemplates:templateId']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao modificar modelo de pesquisa de satisfação. " . $e->getMessage() . ". id: " . $_POST['jsontemplates:templateId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);