<?php
require_once("checkLogin.php");
require_once("../model/database/professors2.database.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

if (isset($_POST["btnApprove"]) && checkUserPermission("PROFE", 6))
{
	$messages = [];
	try
	{
		if (setWorkProposalStatus($_POST['workProposalId'], 1, $_POST['txtFeedbackMessage']))
		{
			$messages[] = "Proposta de docente aprovada!";
			writeLog("Proposta de docente aprovada. id: " . $_POST['workProposalId']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao aprovar proposta de docente: {$e->getMessage()}. id: " . $_POST['workProposalId']);
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_POST['workProposalId'], [ 'messages' => $messagesString ]), true, 303);
}
else if (isset($_POST["btnReject"]) && checkUserPermission("PROFE", 6))
{
	$messages = [];
	try
	{
		if (setWorkProposalStatus($_POST['workProposalId'], 0, $_POST['txtFeedbackMessage']))
		{
			$messages[] = "Proposta de docente rejeitada!";
			writeLog("Proposta de docente rejeitada. id: " . $_POST['workProposalId']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao rejeitar proposta de docente: {$e->getMessage()}. id: " . $_POST['workProposalId']);
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_POST['workProposalId'], [ 'messages' => $messagesString ]), true, 303);
}