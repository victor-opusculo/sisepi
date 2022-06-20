<?php
require_once("../model/database/students.database.php");
require_once("../model/GenericObjectFromDataRow.class.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

$eventDateId = isset($_GET['eventDateId']) && is_numeric($_GET['eventDateId']) ? $_GET['eventDateId'] : 0;
$eventDateObject = new GenericObjectFromDataRow(getEventDate($eventDateId));

$messages = [];
if (isset($_POST["btnsubmitSubmitNoSubscription"]) && $_POST["hiddenListPassword"] === $eventDateObject->presenceListPassword)
{
	$signed = false;
	if ($eventDateObject->isPresenceListOpen)
		try
		{
			if (insertPresenceRecordNoSubs($_POST))
			{
				$messages[] = "Você assinou a lista com sucesso!";
				$signed = true;
				writeLog("Lista de presença assinada (evento sem inscrição). Nome: $_POST[txtName]. E-mail: $_POST[txtEmail]. Evento id: $_POST[eventId]. Data de evento id: $_POST[eventDateId]"); 
			}
			else
				throw new Exception("Lista de presença não assinada.");
		}
		catch (Exception $e)
		{
			$messages[] = $e->getMessage();
			writeErrorLog("Ao assinar lista de presença (evento sem inscrição): {$e->getMessage()} Nome: $_POST[txtName]. E-mail: $_POST[txtEmail]. Evento id: $_POST[eventId]. Data de evento id: $_POST[eventDateId]"); 
		}
		
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'eventDateId' => $_GET['eventDateId'], 'signed' => (int)$signed, 'messages' => $messagesString]), true, 303);
}
else if (isset($_POST["btnsubmitSubmitCommon"]) && $_POST["hiddenListPassword"] === $eventDateObject->presenceListPassword)
{
	$signed = false;
	if ($eventDateObject->isPresenceListOpen)
		try
		{
			if (insertPresenceRecord($_POST))
			{
				$messages[] = "Você assinou a lista com sucesso!";
				$signed = true;
				writeLog("Lista de presença assinada. Inscrito id: $_POST[selName]. Evento id: $_POST[eventId]. Data de evento id: $_POST[eventDateId]"); 
			}
			else
				throw new Exception("Lista de presença não assinada.");
		}
		catch (Exception $e)
		{
			$messages[] = $e->getMessage();
			writeErrorLog("Ao assinar lista de presença: {$e->getMessage()} Inscrito id: $_POST[selName]. Evento id: $_POST[eventId]. Data de evento id: $_POST[eventDateId]"); 
		}
		
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'eventDateId' => $_GET['eventDateId'], 'signed' => (int)$signed, 'messages' => $messagesString]), true, 303);
}

