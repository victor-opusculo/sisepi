<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/database.php");
require_once "../model/Notifications/SentNotification.php";

if(isset($_POST["btnsubmitDeleteNotification"]))
{
	$messages = [];
	$conn = createConnectionAsEditor();
	try
	{
		$getter = new \SisEpi\Model\Notifications\SentNotification();
		$getter->id = $_POST['notId'];
		$notification = $getter->getSingle($conn);
		$deleteResult = $notification->delete($conn);
		if($deleteResult['affectedRows'] > 0)
		{
			$messages[] = "Notificação excluída com sucesso!";
			writeLog("Notificação excluída. id: $_POST[notId] usuário: $_SESSION[username]");
		}
		else
		{
			throw new Exception("Erro: Notificação não excluída.");
		}
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao excluir notificação: {$e->getMessage()}. id: " . $_POST["notId"]);
	}
	finally { $conn->close(); }
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$queryMessages"), true, 303);
}