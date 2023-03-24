<?php

require_once "../vendor/autoload.php";
require_once "../includes/common.php";
require_once "../includes/logEngine.php";
require_once "checkLogin.php";
require_once "../includes/URL/URLGenerator.php";

if (isset($_POST['btnsubmitSubmitSchool']) && checkUserPermission("VMSCH", 3) )
{
    $messages = [];
    $conn = \SisEpi\Model\Database\Connection::create();
    try
    {
        $school = new \SisEpi\Model\VereadorMirim\School();
        $school->fillPropertiesFromFormInput($_POST);
        $school->setCryptKey(\SisEpi\Model\Database\Connection::getCryptoKey());
        $result = $school->save($conn);

        if ($result['affectedRows'] > 0)
        {
            $messages[] = "Escola de vereador mirim editada com sucesso!";
            writeLog("Escola de vereador mirim editada. Id: " . $school->id);
        }
        else
            throw new Exception("Nenhum dado alterado!");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao editar escola de vereador mirim: {$e->getMessage()} ");
    }
    finally { $conn->close(); }

    $queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], $_GET['id'], [ 'messages' => $queryMessages ] ), true, 303);
}
else
	die(noPermissionMessage);