<?php

use SisEpi\Model\Database\Connection;

require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../includes/professorLoginCheck.php");
require_once "../../vendor/autoload.php";

if (isset($_POST["btnsubmitProfessorEditPersonalInfos"]))
{
	$messages = [];
	
	$_POST['professors:profId'] = $_SESSION['professorid'];

	$conn = Connection::create();
	try
	{
		$prof = new \SisEpi\Pub\Model\Professors\Professor();
		$prof->setCryptKey(Connection::getCryptoKey());
		$prof->fillPropertiesFromFormInput($_POST);

		$updateResult = $prof->save($conn);
		if ($updateResult['affectedRows'] > 0)
		{
			$messages[] = "Cadastro atualizado!";
			writeLog("Dados de docente atualizados pelo próprio docente. id: " . $_POST['professors:profId'] . ". Nome: " . $_POST['professors:txtName']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Docente ao atualizar os próprios dados : {$e->getMessage()}. id: " . $_POST['professors:profId'] . ". Nome: " . $_POST['professors:txtName']);
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, "messages=$messagesString"), true, 303);
}