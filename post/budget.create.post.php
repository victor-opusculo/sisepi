<?php

use SisEpi\Model\Database\Connection;

require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";
require_once "../includes/logEngine.php";
require_once "../vendor/autoload.php";

if (isset($_POST["btnsubmitSubmitBudgetEntry"]) && checkUserPermission("BUDGT", 2))
{
	$messages = [];
	$conn = Connection::create();
	try
	{
        $entry = new \SisEpi\Model\Budget\BudgetEntry();
		$entry->fillPropertiesFromFormInput($_POST);

		$insertResult = $entry->save($conn);

		if ($insertResult['newId'])
		{
			$messages[] = "Dotação criada!";
			writeLog("Dotação criada. id: " . $insertResult['newId']);
		}
		else
			throw new Exception("Não foi possível criar a dotação.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao criar dotação de orçamento: {$e->getMessage()}");
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'messages' => $messagesString ]), true, 303);
}
else
	die(noPermissionMessage);