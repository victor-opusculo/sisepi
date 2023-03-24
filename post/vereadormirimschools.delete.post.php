<?php

require_once "../vendor/autoload.php";
require_once "../includes/common.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

use SisEpi\Model\Database\Connection;

if (isset($_POST['btnsubmitDeleteVmSchool']) && checkUserPermission("VMSCH", 4) )
{
    $messages = [];
    $conn = Connection::create();
    try
    {
        $getter = new \SisEpi\Model\VereadorMirim\School();
        $getter->fillPropertiesFromFormInput($_POST);
        $getter->setCryptKey(Connection::getCryptoKey());
        $stu = $getter->getSingle($conn);
        $result = $stu->delete($conn);

        if ($result['affectedRows'] > 0)
        {
            $messages[] = "Escola de vereador mirim excluída com sucesso!";
            writeLog("Escola de vereador mirim excluída. Id: " . $stu->id);
        }
        else
            throw new Exception("Escola de vereador mirim não excluída.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao excluir escola de vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'messages' => $queryMessages ] ), true, 303);
}
else
	die(noPermissionMessage);