<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/database.php");
require_once '../model/traits/EntityTrait.php';

if(isset($_POST["btnsubmitSubmitTrait"]) && checkUserPermission("TRAIT", 3))
{
	$messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $trait = new \Model\Traits\EntityTrait();
        $trait->fillPropertiesFromFormInput($_POST, $_FILES);
        $updateResult = $trait->save($conn);
        if($updateResult['affectedRows'] > 0)
        {
            $messages[] = "Traço editado com sucesso!";
            writeLog("Traço editado id: {$trait->id}.");
        }
        else
            throw new Exception("Traço não editado.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao editar traço: {$e->getMessage()}.");
    }
    finally { $conn->close(); }
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('traits', 'edit', $_GET['id'] ?? 0, [ 'messages' => $queryMessages ] ), true, 303);
}
else
	die(noPermissionMessage);