<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/libraryreservations.database.php");

if(isset($_POST["btnsubmitDeleteReservation"]) && checkUserPermission("LIBR", 14))
{
	$messages = [];
	if(deleteReservation($_POST["reservationId"]))
	{
		$messages[] = "Reserva excluída com sucesso!";
		writeLog("Biblioteca: Reserva excluída. id: " . $_POST["reservationId"]);
	}
	else
	{
		$messages[] = "Erro: Reserva não excluída.";
		writeErrorLog("Biblioteca: Ao excluir reserva. id: " . $_POST["reservationId"]);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);