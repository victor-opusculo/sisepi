<?php
require_once("checkLogin.php");
require_once("../model/database/professors2.database.php");
require_once("../model/DatabaseEntity.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

if (isset($_POST["btnsubmitSubmitWorkProposal"]) && checkUserPermission("PROFE", 7))
{
	$messages = [];
	try
	{
        $dbEntity = new DatabaseEntity('ProfessorWorkProposal', $_POST);
		if (updateSingleWorkProposal($dbEntity, $_FILES, 'fileProposalFile'))
		{
			$messages[] = "Plano de aula alterado!";
			writeLog("Plano de aula alterado. id: " . $_POST['professorworkproposals:profWorkProposalId']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao alterar plano de aula: {$e->getMessage()}. id: " . $_POST['professorworkproposals:profWorkProposalId']);
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_POST['professorworkproposals:profWorkProposalId'], [ 'messages' => $messagesString ]), true, 303);
}