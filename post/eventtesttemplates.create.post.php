<?php

use SisEpi\Model\Database\Connection;

require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once "../vendor/autoload.php";

if(isset($_POST["btnsubmitSubmitTestTemplate"]) && checkUserPermission("EVTST", 2))
{
	$messages = [];
    $conn = Connection::create();
	try
	{
		$template = new \SisEpi\Model\Events\EventTestTemplate();
        $template->fillPropertiesFromFormInput($_POST);
        $updateResult = $template->save($conn);
		if($updateResult['newId'])
		{
			$messages[] = "Modelo de avaliação criado com sucesso!";
			writeLog("Modelo de avaliação criado. id: " . $updateResult['newId']);
		}
		else
			throw new Exception("Modelo de avaliação não criado!");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao criar modelo de avaliação. " . $e->getMessage());
	}
    finally { $conn->close(); }
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);