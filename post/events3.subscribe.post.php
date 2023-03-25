<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/students.database.php");

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("EVENT", 12))
{
	$messages = [];
    try
    {
        $insertResult = createSubscription($_POST);
        if($insertResult['isCreated'])
        {
            $messages[] = "Inscrição criada com sucesso!";
            writeLog("Inscrição criada pela administração. id: $insertResult[newId]. Evento id: $_POST[eventId]");
        }
        else
            throw new Exception("Erro: Inscrição não criada.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao criar inscrição pela administração: {$e->getMessage()}. Evento id: $_POST[eventId]");
    }
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);