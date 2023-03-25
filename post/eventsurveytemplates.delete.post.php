<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/eventsurveys.database.php");

if(isset($_POST["btnsubmitDelete"]) && checkUserPermission("SRVEY", 3))
{
	$messages = [];
	try
	{
		if(deleteSurveyTemplate($_POST['surveyTemplateId']))
		{
			$messages[] = "Modelo de pesquisa de satisfação excluído com sucesso!";
			writeLog("Modelo de pesquisa de satisfação excluído. id: " . $_POST['surveyTemplateId']);
		}
		else
			throw new Exception("Modelo de pesquisa de satisfação não excluído.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir modelo de pesquisa de satisfação. " . $e->getMessage() . ". id: " . $_POST['surveyTemplateId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);