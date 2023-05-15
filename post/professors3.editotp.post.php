<?php

use SisEpi\Model\Database\Connection;
use SisEpi\Model\Professors\ProfessorOTP;

require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once "../vendor/autoload.php";

if (isset($_POST["btnsubmitEditOtp"]) && checkUserPermission("PROFE", 2))
{
	$messages = [];
    $conn = Connection::create();
	try
	{
        $otp = new ProfessorOTP();
        $otp->fillPropertiesFromFormInput($_POST);
        $otp->hashPassword();
        $otp->combineExpiryDateAndTime();
        $updateResult = $otp->save($conn);
		if ($updateResult['affectedRows'] > 0)
		{
			$messages[] = "OTP de docente editada!";
			writeLog("OTP de docente editada. id: " . $otp->id);
		}
		else
			throw new Exception("OTP não alterada!");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao editar OTP de docente: {$e->getMessage()}. id: " . $_POST['professorsotps:hidOtpId']);
	}
    finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $messagesString ]), true, 303);
}