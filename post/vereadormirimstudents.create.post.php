<?php

require_once "../model/database/database.php";
require_once "../model/vereadormirim/Student.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitSubmitStudent']) && checkUserPermission("VMSTU", 2) )
{
    $messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $newStudent = new \Model\VereadorMirim\Student();
        $newStudent->fillPropertiesFromFormInput($_POST, $_FILES);
        $newStudent->setCryptKey(getCryptoKey());
        $result = $newStudent->save($conn);

        if ($result['newId'])
        {
            $messages[] = "Vereador mirim criado com sucesso!";
            writeLog("Vereador mirim criado. Id: " . $result['newId']);
        }
        else
            throw new Exception("Vereador mirim não criado!");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao criar vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'messages' => $queryMessages, 'title' => $_GET['title'] ] ), true, 303);
}
else
	die(noPermissionMessage);