<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/eventsurveys.database.php");

if(isset($_POST["btnsubmitDelete"]) && checkUserPermission("SRVEY", 5))
{
	$messages = [];
	try
	{
		if(deleteSingleAnsweredSurvey($_POST['surveyId']))
		{
			$messages[] = "Pesquisa de satisfação excluída com sucesso!";
			writeLog("Pesquisa de satisfação excluída. id: " . $_POST['surveyId']);
		}
		else
			throw new Exception("Pesquisa de satisfação não excluída.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir pesquisa de satisfação. " . $e->getMessage() . ". id: " . $_POST['surveyId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);