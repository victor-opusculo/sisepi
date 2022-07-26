<?php
require_once("checkLogin.php");
require_once("../model/database/professors2.database.php");
require_once("../model/DatabaseEntity.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

if (isset($_POST["btnsubmitSubmitWorkSheet"]) && checkUserPermission("PROFE", 7))
{
	$messages = [];
	try
	{
        $dbEntity = new DatabaseEntity('ProfessorWorkSheet', $_POST);
		if (updateWorkSheet($dbEntity))
		{
			$messages[] = "Ficha de trabalho de docente editada!";
			writeLog("Ficha de trabalho de docente editada. id: " . $_POST['professorworksheets:profWorkSheetId']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao editar ficha de trabalho de docente: {$e->getMessage()}. id: " . $_POST['professorworksheets:profWorkSheetId']);
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'workSheetId' => $_POST['professorworksheets:profWorkSheetId'], 'messages' => $messagesString ]), true, 303);
}