<?php
require_once("../model/database/students.database.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

if (isset($_POST["btnsubmitSubmitSubscription"]))
{
	$messages = [];
	try
	{
		$created = createSubscription($_POST);
		if ($created['isCreated'])
		{
			$messages[] = "Inscrição feita com sucesso!";
			writeLog("Inscrição em evento feita. Inscrito id: $created[newId]. Evento id: $_POST[eventId]");
		}
		else
			throw new Exception("Inscrição não feita.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao fazer inscrição em evento: {$e->getMessage()}. Nome: $_POST[txtName]. Evento id: $_POST[eventId]");
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'eventId' => $_GET['eventId'], 'messages' => $messagesString ]), true, 303);
}