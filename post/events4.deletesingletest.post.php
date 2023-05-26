<?php

use SisEpi\Model\Database\Connection;
use SisEpi\Model\Events\EventCompletedTest;

require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once "../vendor/autoload.php";

if(isset($_POST["btnsubmitDelete"]) && checkUserPermission("EVTST", 6))
{
	$messages = [];
    $conn = Connection::create();
	try
	{
        $test = new EventCompletedTest();
        $test->id = $_POST['testId'];
        $ctest = $test->getSingle($conn);

        $deleteResult = $ctest->delete($conn);
		if($deleteResult['affectedRows'])
		{
			$messages[] = "Avaliação excluída com sucesso!";
			writeLog("Avaliação excluída. id: " . $_POST['testId']);
		}
		else
			throw new Exception("Avaliação não excluída.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir questionário de avaliação. " . $e->getMessage() . ". id: " . $_POST['testId']);
	}
    finally { $conn->close(); }
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);