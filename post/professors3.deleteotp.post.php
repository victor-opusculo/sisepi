<?php

use SisEpi\Model\Database\Connection;
use SisEpi\Model\Professors\ProfessorOTP;

require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once "../vendor/autoload.php";


if (isset($_POST["btnsubmitDeleteOtp"]) && checkUserPermission("PROFE", 2))
{
	$messages = [];
    $conn = Connection::create();
	try
	{
        $otp = new ProfessorOTP();
        $otp->fillPropertiesFromFormInput($_POST);
        $deleteResult = $otp->delete($conn);
		if ($deleteResult['affectedRows'] > 0)
		{
			$messages[] = "OTP de docente excluída!";
			writeLog("OTP de docente excluída. id: " . $otp->id);
		}
		else
			throw new Exception("OTP não excluída!");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir OTP de docente: {$e->getMessage()}. id: " . $_POST['professorsotps:hidOtpId']);
	}
    finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $messagesString ]), true, 303);
}