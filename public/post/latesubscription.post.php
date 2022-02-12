<?php
require_once("../model/database/students.database.php");
require_once("../includes/URL/URLGenerator.php");

if (isset($_POST["btnsubmitSubmitLateSubscription"]))
{
	$messages = [];
	
	try
	{
		if(createLateSubscription($_POST))
			$messages[] = "Inscrição feita com sucesso!";
		else
			$messages[] = "Não foi possível fazer a inscrição.";
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, [ 'eventId' => $_GET['eventId'], 'backToPresenceListEventId' => $_GET['backToPresenceListEventId'], 'messages' => $messagesString ]), true, 303);
	
}