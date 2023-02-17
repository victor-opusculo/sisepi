<?php

require_once "../includes/common.php";
require_once "../model/database/database.php";
require_once "../model/vereadormirim/Student.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitSubmitStudent']) && checkUserPermission("VMSTU", 3) )
{
    $messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $editedStudent = new \Model\VereadorMirim\Student();
        $editedStudent->fillPropertiesFromFormInput($_POST, $_FILES);
        $editedStudent->setCryptKey(getCryptoKey());
        $result = $editedStudent->save($conn);

        if ($result['affectedRows'] > 0)
        {
            $messages[] = "Vereador mirim editado com sucesso!";
            writeLog("Vereador mirim editado. Id: " . $editedStudent->id);
        }
        else
            throw new Exception("Nenhum dado alterado.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao editar vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $queryMessages ] ), true, 303);
}
else
	die(noPermissionMessage);