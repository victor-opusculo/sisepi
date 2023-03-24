<?php

require_once "../vendor/autoload.php";
require_once "../includes/common.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitSubmitSchool']) && checkUserPermission("VMSCH", 2) )
{
    $messages = [];
    $conn = \SisEpi\Model\Database\Connection::create();
    try
    {
        $newSchool = new \SisEpi\Model\VereadorMirim\School();
        $newSchool->fillPropertiesFromFormInput($_POST);
        $newSchool->setCryptKey(\SisEpi\Model\Database\Connection::getCryptoKey());
        $result = $newSchool->save($conn);

        if ($result['newId'])
        {
            $messages[] = "Escola de vereador mirim criada com sucesso!";
            writeLog("Escola de vereador mirim criada. Id: " . $result['newId']);
        }
        else
            throw new Exception("Escola de vereador mirim não criada!");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao criar escola de vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'messages' => $queryMessages ] ), true, 303);
}
else
	die(noPermissionMessage);