<?php

use SisEpi\Model\Database\Connection;
use SisEpi\Model\Professors\ProfessorInformeRendimentosAttachment;

require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once "../vendor/autoload.php";

if (isset($_POST["btnsubmitSubmitIr"]) && checkUserPermission("PROFE", 14))
{
	$messages = [];
	$conn = Connection::create();
	try
	{
        $attach = new ProfessorInformeRendimentosAttachment();
        $attach->fillPropertiesFromFormInput($_POST, $_FILES);

		if ($attach->exists($conn))
            throw new Exception("Já existe um informe de rendimentos para o ano-calendário escolhido.");

		$insertResult = $attach->save($conn);
		if ($insertResult['newId'])
		{
			$messages[] = "Informe de rendimentos cadastrado!";
			writeLog("Informe de rendimentos de docente cadastrado. id: " . $insertResult['newId']);
		}
		else
			throw new Exception("Não foi possível cadastrar o informe de rendimentos.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao cadastrar informe de rendimentos de docente: {$e->getMessage()}");
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $messagesString ]), true, 303);
}