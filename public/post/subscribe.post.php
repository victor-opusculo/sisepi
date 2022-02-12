<?php
require_once("../model/database/students.database.php");
require_once("../includes/URL/URLGenerator.php");

if (isset($_POST["btnsubmitSubmitSubscription"]))
{
	$messages = [];
	try
	{
		$created = createSubscription($_POST);
		if ($created) $messages[] = "Inscrição feita com sucesso!";
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'eventId' => $_GET['eventId'], 'messages' => $messagesString ]), true, 303);
}