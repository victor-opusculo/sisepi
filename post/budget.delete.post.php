<?php

use SisEpi\Model\Database\Connection;

require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";
require_once "../includes/logEngine.php";
require_once "../vendor/autoload.php";

if (isset($_POST["btnsubmitDelete"]) && checkUserPermission("BUDGT", 4))
{
	$messages = [];
	$conn = Connection::create();
	try
	{
        $entry = new \SisEpi\Model\Budget\BudgetEntry();
		$entry->fillPropertiesFromFormInput($_POST);

		$gotten = $entry->getSingle($conn);
		$deleteResult = $gotten->delete($conn);

		if ($deleteResult['affectedRows'] > 0)
		{
			$messages[] = "Dotação excluída!";
			writeLog("Dotação excluída. id: " . $gotten->id);
		}
		else
			throw new Exception("Não foi possível excluir a dotação.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir dotação de orçamento: {$e->getMessage()}");
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'messages' => $messagesString ]), true, 303);
}
else
	die(noPermissionMessage);