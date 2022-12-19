<?php

require_once "../model/database/database.php";
require_once "../model/vereadormirim/Legislature.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitCreateLegislature']) && checkUserPermission("VMLEG", 2) )
{
    $messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $newLeg = new \Model\VereadorMirim\Legislature();
        $newLeg->fillPropertiesFromFormInput($_POST);
        $result = $newLeg->save($conn);

        if ($result['newId'])
        {
            $messages[] = "Legislatura criada com sucesso!";
            writeLog("Legislatura de vereador mirim criada. Id: " . $result['newId']);
        }
        else
            throw new Exception("Legislatura não criada!");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao criar legislatura de vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'messages' => $queryMessages, 'title' => $_GET['title'] ] ), true, 303);
}
else
	die(noPermissionMessage);