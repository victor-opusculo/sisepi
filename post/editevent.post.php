<?php

require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once "../model/database/database.php";
require_once "../model/events/Event.php";

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("EVENT", 2))
{
	$messages = [];
	$conn = createConnectionAsEditor();
	try
	{
		/*$dbEntities =
		[
			'main' => new DatabaseEntity("event", $_POST),
			'workPlan' => new DatabaseEntity("eventworkplan", $_POST),
			'checklist' => new DatabaseEntity("eventchecklists", $_POST)
		];*/

		$event = new \SisEpi\Model\Events\Event();
		$event->fillPropertiesFromFormInput($_POST, $_FILES);
		
		if($event->save($conn) > 0)
		{
			$messages[] = "Evento alterado com sucesso!";
			writeLog("Evento alterado. id: " . $event->id);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (EventFileUploadException $e)
	{
		$messages[] = "Evento alterado, porém com erro.";
		$messages[] = $e->getMessage();
		writeErrorLog("Evento alterado com erro. " . $e->getMessage() . " id: " . $e->eventId);
	}
	catch (\Model\EventWorkPlanAttachments\FileUploadException $wpe)
	{
		$messages[] = "Evento alterado, porém com erro.";
		$messages[] = $e->getMessage();
		writeErrorLog("Ao efetuar upload de anexo privado. " . $e->getMessage() . " Plano de trabalho id: " . $wpe->workPlanId);
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeLog("Evento não alterado. id: " . $_POST['events:eventId']); 
	}
	finally { $conn->close(); }
	
	$messagesQuery = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$messagesQuery"), true, 303);
}
else
	die(noPermissionMessage);