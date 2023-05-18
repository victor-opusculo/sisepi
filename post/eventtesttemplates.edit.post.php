<?php

use SisEpi\Model\Database\Connection;

require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once "../vendor/autoload.php";

if(isset($_POST["btnsubmitSubmitTestTemplate"]) && checkUserPermission("EVTST", 3))
{
	$messages = [];
    $conn = Connection::create();
	try
	{
		$template = new \SisEpi\Model\Events\EventTestTemplate();
        $template->fillPropertiesFromFormInput($_POST);
        $updateResult = $template->save($conn);
		if($updateResult['affectedRows'] > 0)
		{
			$messages[] = "Modelo de avaliação modificado com sucesso!";
			writeLog("Modelo de avaliação modificado. id: " . $template->id);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao modificar modelo de avaliação. " . $e->getMessage() . ". id: " . $_POST['eventtesttemplate:hidTestTemplateId']);
	}
    finally { $conn->close(); }
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);