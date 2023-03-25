<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/libraryreservations.database.php");

if(isset($_POST["btnsubmitCreateReservation"]) && checkUserPermission("LIBR", 15))
{
	$messages = [];
	try
	{
		$createResResult = createReservation($_POST["numPubId"], $_POST["numUserId"], $_POST["hidReservationDatetime"]);
		if($createResResult['isCreated'])
		{
			$messages[] = "Reserva criada com sucesso!";
			writeLog("Biblioteca: Reserva criada. id: " . $createResResult['newId']);
		}
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Biblioteca: Ao criar reserva! " . $e->getMessage());
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);