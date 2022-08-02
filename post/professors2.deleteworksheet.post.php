<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/professors2.database.php");

if(isset($_POST["btnsubmitDeleteWorkSheet"]) && checkUserPermission("PROFE", 12))
{
	$messages = [];
	try
	{
		if(deleteWorkSheet($_POST["workSheetId"]))
		{
			$messages[] = "Ficha de trabalho de docente excluída com sucesso!";
			writeLog("Ficha de trabalho de docente excluída. id: " . $_POST["workSheetId"]);
		}
		else
			throw new Exception("Erro: Ficha de trabalho de docente não excluída.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir ficha de trabalho de docente. {$e->getMessage()} id: " . $_POST["workSheetId"]);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'title' => $_GET['title'] ?? '', 'messages' => $queryMessages ]), true, 303);
}
else
	die(noPermissionMessage);