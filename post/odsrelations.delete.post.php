<?php

use SisEpi\Model\Database\Connection;

require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";
require_once "../includes/logEngine.php";
require_once "../vendor/autoload.php";

if (isset($_POST["btnsubmitDelete"]) && checkUserPermission("ODSRL", 4))
{
	$messages = [];
	$conn = Connection::create();
	try
	{
        $rel = new \SisEpi\Model\Ods\OdsRelation();
		$rel->fillPropertiesFromFormInput($_POST);

		$gotten = $rel->getSingle($conn);
		$deleteResult = $gotten->delete($conn);

		if ($deleteResult['affectedRows'] > 0)
		{
			$messages[] = "Relação ODS excluída!";
			writeLog("Relação ODS excluída. id: " . $gotten->id);
		}
		else
			throw new Exception("Não foi possível excluir a relação ODS.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir relação ODS: {$e->getMessage()}");
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'messages' => $messagesString ]), true, 303);
}
else
	die(noPermissionMessage);