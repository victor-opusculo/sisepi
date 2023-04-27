<?php

use SisEpi\Model\Database\Connection;

require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";
require_once "../includes/logEngine.php";
require_once "../vendor/autoload.php";

if (isset($_POST["btnsubmitSubmitOdsRelation"]) && checkUserPermission("ODSRL", 3))
{
	$messages = [];
	$conn = Connection::create();
	try
	{
        $rel = new \SisEpi\Model\Ods\OdsRelation();
		$rel->fillPropertiesFromFormInput($_POST);

		$updateResult = $rel->save($conn);

		if ($updateResult['affectedRows'] > 0)
		{
			$messages[] = "Relação ODS editada!";
			writeLog("Relação ODS editada. id: " . $rel->id);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao editar Relação ODS: {$e->getMessage()}");
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $messagesString ]), true, 303);
}
else
	die(noPermissionMessage);