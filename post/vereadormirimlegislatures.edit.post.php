<?php

require_once "../model/database/database.php";
require_once "../model/vereadormirim/Legislature.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitEditLegislature']) && checkUserPermission("VMLEG", 3) )
{
    $messages = [];
    $conn = createConnectionAsEditor();

    $leg = new \Model\VereadorMirim\Legislature();
    $leg->fillPropertiesFromFormInput($_POST);

    try
    {
        $result = $leg->save($conn);
        if ($result['affectedRows'] > 0)
        {
            $messages[] = "Legislatura editada com sucesso!";
            writeLog("Legislatura de vereador mirim editada. Id: " . $leg->id);
        }
        else
            throw new Exception("Nenhum dado alterado.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao editar legislatura de vereador mirim: {$e->getMessage()}. Id: " . $leg->id);
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $queryMessages ] ), true, 303);
}
else
	die(noPermissionMessage);