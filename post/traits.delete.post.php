<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/database.php");
require_once '../model/traits/EntityTrait.php';

if(isset($_POST["btnsubmitDeleteTrait"]) && checkUserPermission("TRAIT", 4))
{
	$messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $getter = new \SisEpi\Model\Traits\EntityTrait();
        $getter->fillPropertiesFromFormInput($_POST);
        $trait = $getter->getSingle($conn);
        $deleteResult = $trait->delete($conn);
        if($deleteResult['affectedRows'] > 0)
        {
            $messages[] = "Traço excluído com sucesso!";
            writeLog("Traço excluído id: {$trait->id}.");
        }
        else
            throw new Exception("Traço não excluído.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao excluir traço: {$e->getMessage()}.");
    }
    finally { $conn->close(); }
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL('homepage', 'messages', null, [ 'messages' => $queryMessages, 'title' => $_GET['title'] ] ), true, 303);
}
else
	die(noPermissionMessage);