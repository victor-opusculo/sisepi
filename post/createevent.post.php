<?php
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";
require_once "../includes/logEngine.php";
require_once "../model/Database/database.php";
require_once "../model/Events/Event.php";

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("EVENT", 1))
{
	$messages = [];
	$conn = createConnectionAsEditor();
	try
	{
		$event = new \SisEpi\Model\Events\Event();
		$event->fillPropertiesFromFormInput($_POST, $_FILES);
		
		$createEventResult = $event->save($conn);
		if($createEventResult['newId'])
		{
			$messages[] = "Evento criado com sucesso!";
			writeLog("Evento criado. id: " . $createEventResult['newId']);
		}
		else
			throw new Exception("Erro: evento não criado.");
	}
	catch (EventFileUploadException $e)
	{
		$messages[] = "Evento criado, porém com erro.";
		$messages[] = $e->getMessage();
		writeErrorLog("Evento criado com erro. id: " . $e->eventId);
	}
	catch (\Model\EventWorkPlanAttachments\FileUploadException $wpe)
	{
		$messages[] = "Evento criado, porém com erro.";
		$messages[] = $e->getMessage();
		writeErrorLog("Ao efetuar upload de anexo privado. Plano de trabalho id: " . $wpe->workPlanId);
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao criar evento! " . $e->getMessage());
	}
	finally { $conn->close(); }
	
	$messagesQuery = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$messagesQuery"), true, 303);
}
else
	die(noPermissionMessage);