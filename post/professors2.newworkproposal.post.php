<?php
require_once("checkLogin.php");
require_once("../model/database/professors2.database.php");
require_once("../model/DatabaseEntity.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

if (isset($_POST["btnsubmitSubmitWorkProposal"]) && checkUserPermission("PROFE", 8))
{
	$messages = [];
	try
	{
        $dbEntity = new DatabaseEntity('ProfessorWorkProposal', $_POST);
		$insertResult = insertNewWorkProposal($dbEntity, $_FILES, 'fileProposalFile');
		if ($insertResult['isCreated'])
		{
			$messages[] = "Plano de aula criado!";
			writeLog("Plano de aula criado. id: " . $insertResult['newId']);
		}
		else
			throw new Exception("Não foi possível criar o plano.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao criar plano de aula de docente: {$e->getMessage()}");
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'title' => $_GET['title'] ?? '', 'messages' => $messagesString ]), true, 303);
}