<?php
require_once("checkLogin.php");
require_once("../model/Database/terms.settings.database.php");
require_once("../model/DatabaseEntity.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

if (isset($_POST["btnsubmitSubmitTerm"]) && checkUserPermission("TERMS", 3))
{
	$messages = [];
	$conn = createConnectionAsEditor();
	try
	{
        $dbEntity = new DatabaseEntity('term', $_POST);

		$updateResult = updateTerm($dbEntity, $_FILES, $conn);
		if ($updateResult)
		{
			$messages[] = "Termo editado!";
			writeLog("Termo editado. id: " . $dbEntity->id);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao editar termo: {$e->getMessage()}");
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $messagesString ]), true, 303);
}