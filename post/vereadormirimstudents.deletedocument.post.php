<?php

require_once "../model/database/database.php";
require_once "../model/vereadormirim/Document.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitDeleteVmDocument']) && checkUserPermission("VMSTU", 3) )
{
    $messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $getter = new \Model\VereadorMirim\Document();
        $getter->fillPropertiesFromFormInput($_POST);
        $getter->setCryptKey(getCryptoKey());
        $doc = $getter->getSingle($conn);
        $result = $doc->delete($conn);

        if ($result['affectedRows'] > 0)
        {
            $messages[] = "Documento de vereador mirim excluído com sucesso!";
            writeLog("Documento de vereador mirim excluído. Id: " . $stu->id);
        }
        else
            throw new Exception("Documento de vereador mirim não excluído.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao excluir documento de vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $queryMessages, ] ), true, 303);
}
else
	die(noPermissionMessage);