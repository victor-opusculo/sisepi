<?php
require_once "../includes/common.php";
require_once("checkLogin.php");
require_once("../model/Database/professors2.database.php");
require_once("../includes/logEngine.php");
require_once "../includes/Mail/professorWorkProposalFeedback.php";

if (isset($_POST["btnApprove"]) && checkUserPermission("PROFE", 6))
{
	$messages = [];
	$conn = createConnectionAsEditor();
	try
	{
		if (setWorkProposalStatus($_POST['workProposalId'], 1, $_POST['txtFeedbackMessage'], $conn))
		{
			$messages[] = "Plano de aula aprovado!";
			writeLog("Plano de aula aprovado. id: " . $_POST['workProposalId']);

			if (!empty($_POST['chkSendProfessorEmail']))
			{
				$wpObj = getSingleWorkProposal($_POST['workProposalId'], $conn);
				if (sendEmailProfessorProposalFeedback($wpObj['name'], true, $wpObj['ownerProfessorEmail'], $wpObj['ownerProfessorName'], $_POST['txtFeedbackMessage']))
					writeLog("E-mail enviado a docente informando aprovação de seu plano de aula. id: " . $_POST['workProposalId']);
			}

		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao aprovar plano de aula de docente: {$e->getMessage()}. id: " . $_POST['workProposalId']);
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_POST['workProposalId'], [ 'messages' => $messagesString ]), true, 303);
}
else if (isset($_POST["btnReject"]) && checkUserPermission("PROFE", 6))
{
	$messages = [];
	$conn = createConnectionAsEditor();
	try
	{
		if (setWorkProposalStatus($_POST['workProposalId'], 0, $_POST['txtFeedbackMessage'], $conn))
		{
			$messages[] = "Plano de aula rejeitado!";
			writeLog("Plano de aula rejeitado. id: " . $_POST['workProposalId']);

			if (!empty($_POST['chkSendProfessorEmail']))
			{
				$wpObj = getSingleWorkProposal($_POST['workProposalId'], $conn);
				if (sendEmailProfessorProposalFeedback($wpObj['name'], false, $wpObj['ownerProfessorEmail'], $wpObj['ownerProfessorName'], $_POST['txtFeedbackMessage']))
					writeLog("E-mail enviado a docente informando rejeição de seu plano de aula. id: " . $_POST['workProposalId']);
			}
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao rejeitar plano de aula de docente: {$e->getMessage()}. id: " . $_POST['workProposalId']);
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_POST['workProposalId'], [ 'messages' => $messagesString ]), true, 303);
}