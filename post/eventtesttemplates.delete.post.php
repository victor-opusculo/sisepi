<?php

use SisEpi\Model\Database\Connection;

require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";
require_once "../includes/logEngine.php";
require_once "../vendor/autoload.php";

if(isset($_POST["btnsubmitDelete"]) && checkUserPermission('EVTST', 4))
{
	$messages = [];
	$conn = Connection::create();
	try
	{
		$template = new \SisEpi\Model\Events\EventTestTemplate();
		$template->fillPropertiesFromFormInput($_POST);
        $deleteResult = $template->delete($conn);
		if($deleteResult['affectedRows'] > 0)
		{
			$messages[] = "Modelo de avaliação excluído com sucesso!";
			writeLog("Modelo de avaliação excluído. id: {$template->id}");
		}
		else
		{
			throw new Exception("Erro: Modelo de avaliação não excluído.");
		}
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir modelo de avaliação: {$e->getMessage()}. id: " . $_POST["eventtesttemplate:hidTestTemplateId"]);
	}
	finally { $conn->close(); }
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$queryMessages"), true, 303);
}
else
    die(noPermissionMessage);