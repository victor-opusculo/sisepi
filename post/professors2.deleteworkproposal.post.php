<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/professors2.database.php");

if(isset($_POST["btnsubmitDeleteWorkProposal"]) && checkUserPermission("PROFE", 9))
{
	$messages = [];
	try
	{
		if(deleteWorkProposal($_POST["workProposalId"], (bool)$_POST['chkDeleteWorkSheets'] ?? false))
		{
			$messages[] = "Proposta de trabalho de docente excluída com sucesso!";
			writeLog("Proposta de trabalho de docente excluída. id: " . $_POST["workProposalId"]);
		}
		else
			throw new Exception("Erro: Proposta de trabalho de docente não excluída.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir proposta de trabalho de docente. {$e->getMessage()} id: " . $_POST["workProposalId"]);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'title' => $_GET['title'] ?? '', 'messages' => $queryMessages ]), true, 303);
}
else
	die(noPermissionMessage);