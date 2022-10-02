<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/librarycollection.database.php");
require_once "../model/librarycollection/Publication.php";

if(isset($_POST["btnsubmitDeletePub"]) && checkUserPermission("LIBR", 4))
{
	$messages = [];

	$pubObj = new Model\LibraryCollection\Publication();
	$pubObj->fillPropertiesFromFormInput($_POST);

	$conn = createConnectionAsEditor();
	$deleteResult = $pubObj->delete($conn);
	if($deleteResult['affectedRows'] > 0)
	{
		$messages[] = "Publicação excluída com sucesso!";
		writeLog("Biblioteca: Publicação excluída. id: " . $pubObj->id);
	}
	else
	{
		$messages[] = "Erro: Publicação não excluída.";
		writeErrorLog("Biblioteca: Ao excluir publicação. id: " . $pubObj->id);
	}
	$conn->close();
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);