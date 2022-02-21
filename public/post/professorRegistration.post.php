<?php
require_once("../model/database/professorRegistration.database.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

if (isset($_POST["btnsubmitProfessorRegistration"]))
{
	$messages = [];
	
	try
	{
		$insertResult = insertProfessorData($_POST);
		if ($insertResult['isCreated'])
		{
			$messages[] = "Obrigado por se cadastrar!";
			writeLog("Docente registrado. id: " . $insertResult['newId'] . ". Nome: " . $_POST['txtName']);
		}
		else
			throw new Exception("Cadastro não efetuado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao registrar docente: {$e->getMessage()}. Nome: " . $_POST['txtName']);
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, "messages=$messagesString"), true, 303);
}