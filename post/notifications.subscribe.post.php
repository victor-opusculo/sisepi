<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/database.php");
require_once '../model/notifications/UserNotificationSubscription.php';

if(isset($_POST["btnsubmitSubmitNotificationSubscriptions"]))
{
	$messages = [];
    $conn = createConnectionAsEditor();
    try
    {
        $subsManager = new \SisEpi\Model\Notifications\UserNotificationSubscription();
        $affectedRows = $subsManager->applySubscriptionChanges($conn, $_POST, $_SESSION['userid']);
        if ($affectedRows > 0)
        {
            $messages[] = "Alterações salvas com sucesso!";
            writeLog("Inscrições em notificações alteradas.");
        }
        else
            throw new Exception("Nenhum dado alterado.");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao alterar inscrições em notificações: {$e->getMessage()}.");
    }
    finally { $conn->close(); }
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'messages' => $queryMessages ] ), true, 303);
}