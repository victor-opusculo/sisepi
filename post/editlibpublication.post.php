<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/librarycollection.database.php");

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("LIBR", 3))
{
	$messages = [];
	if(updatePublication($_POST))
	{
		$messages[] = "Publicação alterada com sucesso!";
		writeLog("Biblioteca: Publicação alterada. id: " . $_POST['publicationId']);
	}
	else
	{
		$messages[] = "Nenhum dado alterado";
		writeLog("Biblioteca: Publicação não alterada. id: " . $_POST['publicationId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);