<?php
require_once("../model/database/students.database.php");
require_once("../model/GenericObjectFromDataRow.class.php");
require_once("../includes/URL/URLGenerator.php");

$eventDateId = isset($_GET['eventDateId']) && is_numeric($_GET['eventDateId']) ? $_GET['eventDateId'] : 0;
$eventDateObject = new GenericObjectFromDataRow(getEventDate($eventDateId));

$messages = [];
if (isset($_POST["btnsubmitSubmitNoSubscription"]) && $_POST["hiddenListPassword"] === $eventDateObject->presenceListPassword)
{
	if ($eventDateObject->isPresenceListOpen)
		try
		{
			if (insertPresenceRecordNoSubs($_POST))
				$messages[] = "Você assinou a lista com sucesso!";
		}
		catch (Exception $e)
		{
			$messages[] = $e->getMessage();
		}
		
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'eventDateId' => $_GET['eventDateId'], 'messages' => $messagesString]), true, 303);
}
else if (isset($_POST["btnsubmitSubmitCommon"]) && $_POST["hiddenListPassword"] === $eventDateObject->presenceListPassword)
{
	if ($eventDateObject->isPresenceListOpen)
		try
		{
			if (insertPresenceRecord($_POST))
				$messages[] = "Você assinou a lista com sucesso!";
		}
		catch (Exception $e)
		{
			$messages[] = $e->getMessage();
		}
		
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'eventDateId' => $_GET['eventDateId'], 'messages' => $messagesString]), true, 303);
}

