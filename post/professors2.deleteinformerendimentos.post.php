<?php

use SisEpi\Model\Database\Connection;
use SisEpi\Model\Professors\ProfessorInformeRendimentosAttachment;

require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once "../vendor/autoload.php";

if (isset($_POST["btnsubmitDeleteIr"]) && checkUserPermission("PROFE", 15))
{
	$messages = [];
	$conn = Connection::create();
	try
	{
        $getter = new ProfessorInformeRendimentosAttachment();
        $getter->fillPropertiesFromFormInput($_POST);
        $getter->setCryptKey(Connection::getCryptoKey());
        $attach = $getter->getSingle($conn);

		$deleteResult = $attach->delete($conn);
		if ($deleteResult['affectedRows'] > 0)
		{
			$messages[] = "Informe de rendimentos excluído!";
			writeLog("Informe de rendimentos de docente excluído. id: " . $attach->id);
		}
		else
			throw new Exception("Não foi possível excluir o informe de rendimentos.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir informe de rendimentos de docente: {$e->getMessage()}");
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $messagesString ]), true, 303);
}