<?php
require_once("checkLogin.php");
require_once("../model/database/terms.settings.database.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

if (isset($_POST["btnsubmitDeleteTerm"]) && checkUserPermission("TERMS", 4))
{
	$messages = [];
	$conn = createConnectionAsEditor();
	try
	{
		$deleteResult = deleteTerm($_POST['termId'], $conn);
		if ($deleteResult)
		{
			$messages[] = "Termo excluído!";
			writeLog("Termo excluído. id: " . $dbEntity->id);
		}
		else
			throw new Exception("Não foi possível excluir o termo.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir termo: {$e->getMessage()}");
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL("homepage", "messages", null, [ 'title' => $_GET['title'], 'messages' => $messagesString ]), true, 303);
}