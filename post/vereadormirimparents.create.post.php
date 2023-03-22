<?php

require_once "../includes/common.php";
require_once "../model/database/database.php";
require_once "../model/vereadormirim/VmParent.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitSubmitParent']) && checkUserPermission("VMPAR", 2) )
{
    $messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $newParent = new \SisEpi\Model\VereadorMirim\VmParent();
        $newParent->fillPropertiesFromFormInput($_POST);
        $newParent->setCryptKey(getCryptoKey());
        $result = $newParent->save($conn);

        if ($result['newId'])
        {
            $messages[] = "Responsável de vereador mirim criado com sucesso!";
            writeLog("Responsável de vereador mirim criado. Id: " . $result['newId']);
        }
        else
            throw new Exception("Responsável de vereador mirim não criado!");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao criar responsável de vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'messages' => $queryMessages, 'title' => $_GET['title'] ] ), true, 303);
}
else
	die(noPermissionMessage);