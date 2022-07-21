<?php
require_once("../model/database/professorpanelfunctions.database.php");
require_once("../model/DatabaseEntity.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../includes/professorLoginCheck.php");

if (isset($_POST["btnsubmitSubmitNewWorkProposal"]))
{
	$messages = [];
	try
	{
		$dbEntity = new DatabaseEntity('ProfessorWorkProposal', $_POST);
		$dbEntity->ownerProfessorId = $_SESSION['professorid'];

		$insertResult = insertNewProfessorWorkProposal($dbEntity, $_FILES, 'fileProposalFile');
		if ($insertResult['isCreated'])
		{
			$messages[] = "Proposta enviada com sucesso!";
			writeLog("Proposta de trabalho enviada pelo próprio docente. id: " . $_SESSION['professorid'] . ". Nome: " . $_SESSION['professorname']);
		}
		else
			throw new Exception("Não foi possível salvar a proposta.");
	}
	catch (FileUploadException $fe)
	{
		$messages[] = $fe->getMessage();
		writeErrorLog("Docente ao subir a proposta de trabalho: {$fe->getMessage()}. Docente id: " . $fe->professorId);
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Docente ao subir a proposta de trabalho: {$e->getMessage()}. id: " . $_SESSION['professorid'] . ". Nome: " . $_SESSION['professorname']);
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, "messages=$messagesString"), true, 303);
}