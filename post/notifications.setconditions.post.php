<?php

require_once "./checkLogin.php";
require_once "../model/database/database.php";
require_once "../model/notifications/UserNotificationSubscription.php";
require_once "../includes/logEngine.php";
require_once "../includes/URL/URLGenerator.php";

$messages = [];
if (isset($_POST['btnsubmitSubmitConditions']))
{
    $conn = createConnectionAsEditor();
    try
    {
        $getter = new \Model\Notifications\UserNotificationSubscription();
        $getter->userId = $_SESSION['userid'] ?? 0;
        $getter->notMod = $_POST['hidModule'];
        $getter->notId = $_POST['hidId'];

        $uNotSubs = $getter->getSingle($conn);
        $uNotSubs->notConditions = $_POST['hidConditionsJson'];
        $affectedRows = $uNotSubs->save($conn);

        if ($affectedRows > 0)
        {
            $messages[] = "Condições salvas com sucesso!";
            writeLog("Condições para disparo de notificação alteradas. Not Mod: {$uNotSubs->notMod}. Not Id: {$uNotSubs->notId}");
        }
        else
            throw new Exception("Nenhum dado alterado");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao salvar condições para disparo de notificação. Not Mod: {$uNotSubs->notMod}. Not Id: {$uNotSubs->notId}");
    }
    finally { $conn->close(); }

    $messagesString = implode('//', $messages);
    header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$messagesString"), true, 303);
}
else if (isset($_POST['btnsubmitDeleteConditions']))
{
    $conn = createConnectionAsEditor();
    try
    {
        $getter = new \Model\Notifications\UserNotificationSubscription();
        $getter->userId = $_SESSION['userid'] ?? 0;
        $getter->notMod = $_POST['hidModule'];
        $getter->notId = $_POST['hidId'];

        $uNotSubs = $getter->getSingle($conn);
        $uNotSubs->notConditions = null;
        $affectedRows = $uNotSubs->save($conn);

        if ($affectedRows > 0)
        {
            $messages[] = "Condições excluídas com sucesso!";
            writeLog("Condições para disparo de notificação excluídas. Not Mod: {$uNotSubs->notMod}. Not Id: {$uNotSubs->notId}");
        }
        else
            throw new Exception("Nenhum dado alterado");
    }
    catch (Exception $e)
    {
        $messages[] = $e->getMessage();
        writeErrorLog("Ao excluir condições para disparo de notificação. Not Mod: {$uNotSubs->notMod}. Not Id: {$uNotSubs->notId}");
    }
    finally { $conn->close(); }

    $messagesString = implode('//', $messages);
    header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$messagesString"), true, 303);
}