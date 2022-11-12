<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/database.php");
require_once '../model/traits/EntityTrait.php';

if(isset($_POST["btnsubmitSubmitTrait"]) && checkUserPermission("TRAIT", 2))
{
	$messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $trait = new \Model\Traits\EntityTrait();
        $trait->fillPropertiesFromFormInput($_POST, $_FILES);
        $insertResult = $trait->save($conn);
        if($insertResult['newId'])
        {
            $messages[] = "Traço criado com sucesso!";
            writeLog("Traço criado id: $insertResult[newId].");
        }
        else
            throw new Exception("Erro: Traço não criado.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao criar traço: {$e->getMessage()}.");
    }
    finally { $conn->close(); }
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'messages' => $queryMessages, 'title' => $_GET['title'] ] ), true, 303);
}
else
	die(noPermissionMessage);