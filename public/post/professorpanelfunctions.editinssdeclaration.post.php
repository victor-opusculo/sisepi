<?php
require_once("../model/Database/professorpanelfunctions.database.php");
require_once("../model/DatabaseEntity.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../includes/professorLoginCheck.php");

if (isset($_POST["btnsubmitSubmitInssCompanies"]))
{
	$messages = [];
	
	try
	{
		if (updateInssDeclaration($_POST['workSheetId'], $_POST['inssCompanies']))
		{
			$messages[] = "Declaração de INSS atualizada!";
			writeLog("Declaração de INSS atualizada pelo próprio docente. Ficha de trabalho id: $_POST[workSheetId] . Docente id: $_SESSION[professorid] . Nome: $_SESSION[professorname]");
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Docente ao atualizar a declaração de INSS: {$e->getMessage()}. Ficha de trabalho id: $_POST[workSheetId] . Docente id: $_SESSION[professorid] . Nome: $_SESSION[professorname]");
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, "workSheetId=$_GET[workSheetId]&messages=$messagesString"), true, 303);
}