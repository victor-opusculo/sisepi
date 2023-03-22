<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/database.php");
require_once "../model/notifications/classes/UserMessageNotification.php";

if(isset($_POST["btnsubmitSendMessage"]))
{
	$messages = [];
	$conn = createConnectionAsEditor();
	try
	{
		$notification = new \SisEpi\Model\Notifications\Classes\UserMessageNotification
        ([
            'senderUserId' => $_SESSION['userid'],
            'destinationUserIds' => array_unique($_POST['selDestinationUserId']),
            'messageText' => $_POST['txtMessage'],
            'url' => $_POST['txtURL']
        ]);

		if($notification->push($conn) > 0)
		{
			$messages[] = "Mensagem enviada!";
			writeLog("Mensagem via notificação enviada. Remetente: $_SESSION[username] ($_SESSION[userid]) Destinatários IDs: " . implode(',', $_POST['selDestinationUserId']));
		}
		else
			throw new Exception('Mensagem não enviada!');
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao enviar mensagem via notificação: {$e->getMessage()}. Remetente: $_SESSION[username] ($_SESSION[userid]) ");
	}
	finally { $conn->close(); }
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$queryMessages"), true, 303);
}