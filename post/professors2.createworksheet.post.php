<?php
require_once("checkLogin.php");
require_once("../model/Database/professors2.database.php");
require_once("../model/DatabaseEntity.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

if (isset($_POST["btnsubmitSubmitWorkSheet"]) && checkUserPermission("PROFE", 10))
{
	$messages = [];
	$conn = createConnectionAsEditor();
	try
	{
        $dbEntity = new DatabaseEntity('ProfessorWorkSheet', $_POST);

		if (!isProfessorRegistrationComplete($dbEntity->professorId, $conn))
			throw new Exception('Não foi possível criar a ficha de trabalho. O docente definido não completou o cadastro. Entre em contato com ele e peça para preencher todos os dados.');

		$insertResult = insertWorkSheet($dbEntity, $conn);
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
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'title' => $_GET['title'] ?? '', 'messages' => $messagesString ]), true, 303);
}