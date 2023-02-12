<?php

require_once "../model/database/database.php";
require_once "../model/vereadormirim/Party.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitEditParty']) && checkUserPermission("VMPTY", 3) )
{
    $messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $party = new \Model\VereadorMirim\Party();
        $party->fillPropertiesFromFormInput($_POST, $_FILES);
        $result = $party->save($conn);

        if ($result['affectedRows'] > 0)
        {
            $messages[] = "Partido editado com sucesso!";
            writeLog("Partido de vereador mirim editado. Id: " . $party->id);
        }
        else
            throw new Exception("Nenhum dado alterado.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao editar partido de vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $queryMessages, ] ), true, 303);
}
else
	die(noPermissionMessage);