<?php
require_once("../model/database/students.database.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

if (isset($_POST["btnsubmitSubmitLateSubscription"]))
{
	$messages = [];
	
	try
	{
		if(createLateSubscription($_POST))
		{
			$messages[] = "Inscrição feita com sucesso!";
			writeLog("Inscrição tardia feita. Nome: " . $_POST['txtName'] . ". E-mail: " . $_POST['txtEmail'] . ". Evento id: " . $_POST['eventId']);
		}
		else
			throw new Exception("Não foi possível fazer a inscrição.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Inscrição tardia não realizada: {$e->getMessage()} (Nome: " . $_POST['txtName'] . ". E-mail: " . $_POST['txtEmail'] . ". Evento id: " . $_POST['eventId'] . ")");
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, [ 'eventId' => $_GET['eventId'], 'backToPresenceListEventId' => $_GET['backToPresenceListEventId'], 'messages' => $messagesString ]), true, 303);
	
}