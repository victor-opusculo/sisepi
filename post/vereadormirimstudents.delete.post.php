<?php

require_once "../model/database/database.php";
require_once "../includes/common.php";
require_once "../model/vereadormirim/Student.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitDeleteVmStudent']) && checkUserPermission("VMSTU", 4) )
{
    $messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $getter = new \Model\VereadorMirim\Student();
        $getter->fillPropertiesFromFormInput($_POST);
        $getter->setCryptKey(getCryptoKey());
        $stu = $getter->getSingle($conn);
        $result = $stu->delete($conn);

        if ($result['affectedRows'] > 0)
        {
            $messages[] = "Vereador mirim excluído com sucesso!";
            writeLog("Vereador mirim excluído. Id: " . $stu->id);
        }
        else
            throw new Exception("Vereador mirim não excluído.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao excluir vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', "messages", null, [ 'messages' => $queryMessages, "title" => $_GET['title'] ] ), true, 303);
}
else
	die(noPermissionMessage);