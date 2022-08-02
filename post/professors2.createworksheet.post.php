<?php
require_once("checkLogin.php");
require_once("../model/database/professors2.database.php");
require_once("../model/DatabaseEntity.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

if (isset($_POST["btnsubmitSubmitWorkSheet"]) && checkUserPermission("PROFE", 10))
{
	$messages = [];
	try
	{
        $dbEntity = new DatabaseEntity('ProfessorWorkSheet', $_POST);
		$insertResult = insertWorkSheet($dbEntity);
		if ($insertResult['isCreated'])
		{
			$messages[] = "Ficha de trabalho de docente criada!";
			writeLog("Ficha de trabalho de docente criada. id: " . $insertResult['newId']);
		}
		else
			throw new Exception("Não foi possível criar a ficha de trabalho.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao criar ficha de trabalho de docente: {$e->getMessage()}");
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'title' => $_GET['title'] ?? '', 'messages' => $messagesString ]), true, 303);
}