<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/students.database.php");

if(isset($_POST["btnsubmitMarkPresence"]) && checkUserPermission("EVENT", 8))
{
	$messages = [];
	
	$conn = createConnectionAsEditor();
	if (!checkIfPresenceIsSignedNoSubs($_POST["eventDateId"], $_POST["txtEmail"], $conn))
	{
		if(markPresenceNoSubs($_POST, $conn))
		{
			$messages[] = "Presença marcada com sucesso!";
			writeLog("Presença marcada. Evento id: " . $_POST["eventDateId"] . " e-mail: " . $_POST["txtEmail"]);
		}
		else
		{
			$messages[] = "Erro: Presença não marcada.";
			writeErrorLog("Presença não marcada. Evento id: " . $_POST["eventDateId"] . " e-mail: " . $_POST["txtEmail"]);
		}
	}
	else
		$messages[] = "Presença já marcada!";
	
	$conn->close();
	
	$messagesString = implode("//", $messages);
	$queryEventDateId = $_GET["eventDateId"] ?? 0;
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "eventDateId=$queryEventDateId&messages=$messagesString"), true, 303);
}
else
	die(noPermissionMessage);