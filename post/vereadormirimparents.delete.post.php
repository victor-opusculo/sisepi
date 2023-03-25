<?php

require_once "../includes/common.php";
require_once "../model/Database/database.php";
require_once "../model/VereadorMirim/VmParent.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitDeleteVmParent']) && checkUserPermission("VMPAR", 4) )
{
    $messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $getter = new \SisEpi\Model\VereadorMirim\VmParent();
        $getter->fillPropertiesFromFormInput($_POST);
        $getter->setCryptKey(getCryptoKey());
        $par = $getter->getSingle($conn);
        $result = $par->delete($conn);

        if ($result['affectedRows'] > 0)
        {
            $messages[] = "Responsável de vereador mirim excluído com sucesso!";
            writeLog("Responsável de vereador mirim excluído. Id: " . $par->id);
        }
        else
            throw new Exception("Responsável de vereador mirim não excluído.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao excluir responsável de vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', "messages", null, [ 'messages' => $queryMessages, "title" => $_GET['title'] ] ), true, 303);
}
else
	die(noPermissionMessage);