<?php

require_once "../model/database/database.php";
require_once "../model/vereadormirim/VmParent.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitSubmitParent']) && checkUserPermission("VMPAR", 3) )
{
    $messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $editedParent = new \Model\VereadorMirim\VmParent();
        $editedParent->fillPropertiesFromFormInput($_POST);
        $editedParent->setCryptKey(getCryptoKey());
        $result = $editedParent->save($conn);

        if ($result['affectedRows'] > 0)
        {
            $messages[] = "Responsável de vereador mirim editado com sucesso!";
            writeLog("Responsável de vereador mirim editado. Id: " . $editedParent->id);
        }
        else
            throw new Exception("Nenhum dado alterado.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao editar responsável de vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $queryMessages ] ), true, 303);
}
else
	die(noPermissionMessage);