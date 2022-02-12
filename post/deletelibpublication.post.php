<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/librarycollection.database.php");

if(isset($_POST["btnsubmitDeletePub"]) && checkUserPermission("LIBR", 4))
{
	$messages = [];
	if(deletePublication($_POST["publicationId"]))
	{
		$messages[] = "Publicação excluída com sucesso!";
		writeLog("Biblioteca: Publicação excluída. id: " . $_POST["publicationId"]);
	}
	else
	{
		$messages[] = "Erro: Publicação não excluída.";
		writeErrorLog("Biblioteca: Ao excluir publicação. id: " . $_POST["publicationId"]);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);