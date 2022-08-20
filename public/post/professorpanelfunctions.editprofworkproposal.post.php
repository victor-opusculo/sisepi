<?php
require_once("../model/database/professorpanelfunctions.database.php");
require_once("../model/DatabaseEntity.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../includes/professorLoginCheck.php");

if (isset($_POST["btnsubmitSubmitWorkProposal"]))
{
	$messages = [];
	$conn = createConnectionAsEditor();
	try
	{
		if (!checkForWorkProposalOwnership($_SESSION['professorid'], $_POST['professorworkproposals:profWorkProposalId'], $conn))
			throw new Exception('ID de plano não localizado!');

		$dbEntity = new DatabaseEntity('ProfessorWorkProposalEditable', $_POST);
		$dbEntity->ownerProfessorId = $_SESSION['professorid'];
		$dbEntity->isApproved = null;
		$dbEntity->feedbackMessage = null;

		if (updateWorkProposal($dbEntity, $_FILES, 'fileProposalFile', $conn))
		{
			$messages[] = "Plano atualizado!";
			writeLog("Plano de aula atualizado pelo próprio docente. id: " . $_SESSION['professorid'] . ". Nome: " . $_SESSION['professorname']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (FileUploadException $fe)
	{
		$messages[] = $fe->getMessage();
		writeErrorLog("Docente ao subir o arquivo de plano de aula: {$fe->getMessage()}. id: " . $fe->professorId);
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Docente ao atualizar plano de aula: {$e->getMessage()}. id: " . $_SESSION['professorid'] . ". Nome: " . $_SESSION['professorname']);
	}
	finally
	{
		$conn->close();
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], "messages=$messagesString"), true, 303);
}