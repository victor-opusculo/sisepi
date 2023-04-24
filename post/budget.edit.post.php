<?php

use SisEpi\Model\Database\Connection;

require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";
require_once "../includes/logEngine.php";
require_once "../vendor/autoload.php";

if (isset($_POST["btnsubmitSubmitBudgetEntry"]) && checkUserPermission("BUDGT", 3))
{
	$messages = [];
	$conn = Connection::create();
	try
	{
        $entry = new \SisEpi\Model\Budget\BudgetEntry();
		$entry->fillPropertiesFromFormInput($_POST);

		$insertResult = $entry->save($conn);

		if ($insertResult['affectedRows'] > 0)
		{
			$messages[] = "Dotação editada!";
			writeLog("Dotação editada. id: " . $entry->id);
		}
		else
			throw new Exception("Não foi possível editar a dotação.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao editar dotação de orçamento: {$e->getMessage()}");
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $messagesString ]), true, 303);
}
else
	die(noPermissionMessage);