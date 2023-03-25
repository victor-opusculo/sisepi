<?php

require_once "../model/Database/database.php";
require_once "../model/VereadorMirim/Party.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitCreateParty']) && checkUserPermission("VMPTY", 2) )
{
    $messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $newParty = new \SisEpi\Model\VereadorMirim\Party();
        $newParty->fillPropertiesFromFormInput($_POST, $_FILES);
        $result = $newParty->save($conn);

        if ($result['newId'])
        {
            $messages[] = "Partido criado com sucesso!";
            writeLog("Partido de vereador mirim criado. Id: " . $result['newId']);
        }
        else
            throw new Exception("Partido não criado!");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao criar partido de vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'messages' => $queryMessages, 'title' => $_GET['title'] ] ), true, 303);
}
else
	die(noPermissionMessage);