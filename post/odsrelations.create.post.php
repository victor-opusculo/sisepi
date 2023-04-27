<?php

use SisEpi\Model\Database\Connection;

require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";
require_once "../includes/logEngine.php";
require_once "../vendor/autoload.php";

if (isset($_POST["btnsubmitSubmitOdsRelation"]) && checkUserPermission("ODSRL", 2))
{
	$messages = [];
	$conn = Connection::create();
	try
	{
        $rel = new \SisEpi\Model\Ods\OdsRelation();
		$rel->fillPropertiesFromFormInput($_POST);

		$insertResult = $rel->save($conn);

		if ($insertResult['newId'])
		{
			$messages[] = "Relação ODS criada!";
			writeLog("Relação ODS criada. id: " . $insertResult['newId']);
		}
		else
			throw new Exception("Não foi possível criar a Relação ODS.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao criar Relação ODS: {$e->getMessage()}");
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'messages' => $messagesString ]), true, 303);
}
else
	die(noPermissionMessage);