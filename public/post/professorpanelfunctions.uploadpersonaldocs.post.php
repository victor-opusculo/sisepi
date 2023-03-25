<?php
require_once("../model/Database/professorpanelfunctions.database.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../includes/professorLoginCheck.php");

if (isset($_POST["btnsubmitSubmitProfessorDocs"]))
{
	$messages = [];
	try
	{
		if (updateUploadedPersonalDocs($_SESSION['professorid'], $_POST['hidUploadDocsChangesReport'], $_FILES))
		{
			$messages[] = "Documentos atualizados!";
			writeLog("Arquivos de documentos atualizados pelo próprio docente. id: " . $_SESSION['professorid'] . ". Nome: " . $_SESSION['professorname']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (FileUploadException $fe)
	{
		$messages[] = $fe->getMessage();
		writeErrorLog("Docente ao subir os arquivos de documentos: {$fe->getMessage()}. id: " . $fe->professorId);
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Docente ao atualizar os próprios arquivos de documentos: {$e->getMessage()}. id: " . $_SESSION['professorid'] . ". Nome: " . $_SESSION['professorname']);
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, "messages=$messagesString"), true, 303);
}