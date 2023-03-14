<?php

require_once "../includes/Data/namespace.php";
require_once "../model/database/database.php";
require_once "../model/vereadormirim/Document.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitSubmitDocument']) && checkUserPermission("VMSTU", 3) )
{
    $messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $newDocument = new \Model\VereadorMirim\Document();
        $newDocument->fillPropertiesFromFormInput($_POST);
        $newDocument->setCryptKey(getCryptoKey());
        $result = $newDocument->save($conn);

        if ($result['newId'])
        {
            $messages[] = "Documento de vereador mirim criado com sucesso!";
            writeLog("Documento de vereador mirim criado. Id: " . $result['newId']);
        }
        else
            throw new Exception("Documento não criado.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao criar documento de vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $queryMessages ] ), true, 303);
}
else
	die(noPermissionMessage);