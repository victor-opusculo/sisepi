<?php

use SisEpi\Model\Database\Connection;

require_once("../model/Database/professors.database.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once "../../vendor/autoload.php";

if (isset($_POST["btnsubmitProfessorRegistration"]))
{
	$messages = [];
	$conn = Connection::create();
	try
	{
		$prof = new \SisEpi\Pub\Model\Professors\Professor();
		$prof->fillPropertiesFromFormInput($_POST);
		$prof->setCryptKey(Connection::getCryptoKey());
		$insertResult = $prof->save($conn);
		if ($insertResult['newId'])
		{
			$messages[] = "Obrigado por se cadastrar!";
			writeLog("Docente registrado. id: " . $insertResult['newId'] . ". Nome: " . $_POST['professors:txtName']);
		}
		else
			throw new Exception("Cadastro não efetuado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao registrar docente: {$e->getMessage()}. Nome: " . $_POST['professors:txtName']);
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, "messages=$messagesString"), true, 303);
}