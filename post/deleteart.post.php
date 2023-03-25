<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/artmuseum.database.php");

if(isset($_POST["btnsubmitDeleteArt"]) && checkUserPermission("ARTM", 4))
{
	$messages = [];
	if(deleteFullArt($_POST["artPieceId"]))
	{
		$messages[] = "Obra de arte excluída com sucesso!";
		writeLog("Obra de arte excluída. id: " . $_POST['artPieceId']);
	}
	else
	{
		$messages[] = "Não foi possível excluir a obra de arte.";
		writeErrorLog("Ao excluir obra de arte. id: " . $_POST['artPieceId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);