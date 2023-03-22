<?php
require_once("../model/database/students.database.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

require_once "../../model/notifications/classes/EventSubscriptionNotification.php";

if (isset($_POST["btnsubmitSubmitSubscription"]))
{
	$messages = [];
	$conn = createConnectionAsEditor();
	try
	{
		$created = createSubscription($_POST, $conn);
		if ($created['isCreated'])
		{
			$messages[] = "Inscrição feita com sucesso!";
			writeLog("Inscrição em evento feita. Inscrito id: $created[newId]. Evento id: $_POST[eventId]");
			$notification = new \SisEpi\Model\Notifications\Classes\EventSubscriptionNotification
			([
				'eventId' => $_POST['eventId'],
				'subscriptionId' => $created['newId'],
				'studentEmail' => $_POST['txtEmail'],
				'studentName' => $_POST['txtName'],
				'studentSubscriptionFields' => array_combine($_POST['questIdentifier'], $_POST['questions'])
			]);
			$notification->push($conn);
		}
		else
			throw new Exception("Inscrição não feita.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao fazer inscrição em evento: {$e->getMessage()}. Nome: $_POST[txtName]. Evento id: $_POST[eventId]");
	}
	finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'eventId' => $_GET['eventId'], 'messages' => $messagesString ]), true, 303);
}