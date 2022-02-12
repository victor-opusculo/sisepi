<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/events.database.php");

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("EVENT", 1))
{
	$messages = [];
	try
	{
		$createEventResult = createFullEvent($_POST, $_FILES);
		if($createEventResult['isCreated'])
		{
			$messages[] = "Evento criado com sucesso!";
			writeLog("Evento criado. id: " . $createEventResult['newId']);
		}
		else
			throw new Exception("Erro: evento não criado.");
	}
	catch (FileUploadException $e)
	{
		$messages[] = "Evento criado, porém com erro.";
		$messages[] = $e->getMessage();
		writeErrorLog("Evento criado com erro. id: " . $e->eventId);
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao criar evento! " . $e->getMessage());
	}
	
	$messagesQuery = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$messagesQuery"), true, 303);
}
else
	die(noPermissionMessage);