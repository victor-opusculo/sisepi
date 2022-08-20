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
			$messages[] = "Plano de aula de docente excluído com sucesso!";
			writeLog("Plano de aula de docente excluído. id: " . $_POST["workProposalId"]);
		}
		else
			throw new Exception("Erro: Plano de aula de docente não excluído.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir Plano de aula de docente. {$e->getMessage()} id: " . $_POST["workProposalId"]);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'title' => $_GET['title'] ?? '', 'messages' => $queryMessages ]), true, 303);
}
else
	die(noPermissionMessage);