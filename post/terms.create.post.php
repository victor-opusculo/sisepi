<?php
require_once("checkLogin.php");
require_once("../model/Database/terms.settings.database.php");
require_once("../model/DatabaseEntity.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

if (isset($_POST["btnsubmitSubmitTerm"])  && checkUserPermission("TERMS", 2))
{
	$messages = [];
	$conn = createConnectionAsEditor();
	try
	{
        $dbEntity = new DatabaseEntity('term', $_POST);

		$insertResult = createTerm($dbEntity, $_FILES, $conn);
		if ($insertResult['isCreated'])
		{
			$messages[] = "Termo criado!";
			writeLog("Termo criado. id: " . $insertResult['newId']);
		}
		else
			throw new Exception("Não foi possível criar o termo.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao criar termo: {$e->getMessage()}");
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'title' => $_GET['title'] ?? '', 'messages' => $messagesString ]), true, 303);
}