<?php

require_once "../model/database/database.php";
require_once "../model/vereadormirim/Party.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitDeleteParty']) && checkUserPermission("VMPTY", 4) )
{
    $messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $getter = new \Model\VereadorMirim\Party();
        $getter->fillPropertiesFromFormInput($_POST);
        $party = $getter->getSingle($conn);
        $result = $party->delete($conn);

        if ($result['affectedRows'] > 0)
        {
            $messages[] = "Partido excluído com sucesso!";
            writeLog("Partido de vereador mirim excluído. Id: " . $party->id);
        }
        else
            throw new Exception("Partido não excluído.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao excluir partido de vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', "messages", null, [ 'messages' => $queryMessages, "title" => $_GET['title'] ] ), true, 303);
}
else
	die(noPermissionMessage);