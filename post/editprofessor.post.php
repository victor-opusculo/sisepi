<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/database.php");

require_once "../model/professors/Professor.php";

if(isset($_POST["btnsubmitProfessorEditPersonalInfos"]) && checkUserPermission("PROFE", 2))
{
	$messages = [];

	$conn = createConnectionAsEditor();
	try
	{
		$prof = new \SisEpi\Model\Professors\Professor();
		$prof->fillPropertiesFromFormInput($_POST);
		$prof->setCryptKey(getCryptoKey());
		$updateResult = $prof->save($conn);
		if($updateResult['affectedRows'] > 0)
		{
			$messages[] = "Docente atualizado com sucesso!";
			writeLog("Docente alterado. id: " . $_POST['professors:profId']);
		}
		else
		{
			throw new Exception('Nenhum dado alterado.');
		}
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Docente não alterado: {$e->getMessage()} id: " . $_POST['professors:profId']);
	}
	$conn->close();
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);