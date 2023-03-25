<?php
require_once("checkLogin.php");
require_once("../model/Database/professors.database.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

if (isset($_POST["btnsubmitSubmitProfessorDocs"]) && checkUserPermission("PROFE", 2))
{
	$messages = [];
	try
	{
		if (updateUploadedPersonalDocs($_POST['professorId'], $_POST['hidUploadDocsChangesReport'], $_FILES))
		{
			$messages[] = "Documentos atualizados!";
			writeLog("Arquivos de documentos atualizados. id: " . $_POST['professorId']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (FileUploadException $fe)
	{
		$messages[] = $fe->getMessage();
		writeErrorLog("No upload ao atualizar documentos de docente: {$fe->getMessage()}. id: " . $fe->professorId);
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao atualizar uploads de documentos de docente: {$e->getMessage()}. id: " . $_POST['professorId']);
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, ['professorId' => $_POST['professorId'], 'messages' => $messagesString ]), true, 303);
}