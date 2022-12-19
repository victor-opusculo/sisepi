<?php

require_once "../model/database/database.php";
require_once "../model/vereadormirim/Legislature.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitDeleteLegislature']) && checkUserPermission("VMLEG", 4) )
{
    $messages = [];
    $conn = createConnectionAsEditor();

    $leg = new \Model\VereadorMirim\Legislature();
    $leg->fillPropertiesFromFormInput($_POST);

    try
    {
        $result = $leg->delete($conn);
        if ($result['affectedRows'] > 0)
        {
            $messages[] = "Legislatura excluída com sucesso!";
            writeLog("Legislatura de vereador mirim excluída. Id: " . $leg->id);
        }
        else
            throw new Exception("Legislatura não excluída.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao excluir legislatura de vereador mirim: {$e->getMessage()}. Id: " . $leg->id);
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'messages' => $queryMessages, 'title' => $_GET['title'] ] ), true, 303);
}
else
	die(noPermissionMessage);