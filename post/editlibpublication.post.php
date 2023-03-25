<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/librarycollection.database.php");
require_once '../model/LibraryCollection/Publication.php';

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("LIBR", 3))
{
	$messages = [];
	$pubObj = new SisEpi\Model\LibraryCollection\Publication();
	$pubObj->fillPropertiesFromFormInput($_POST);

	$conn = createConnectionAsEditor();
	$updateResult = $pubObj->save($conn);
	if($updateResult['affectedRows'] > 0)
	{
		$messages[] = "Publicação alterada com sucesso!";
		writeLog("Biblioteca: Publicação alterada. id: " . $pubObj->id);
	}
	else
	{
		$messages[] = "Nenhum dado alterado";
		writeLog("Biblioteca: Publicação não alterada. id: " . $pubObj->id);
	}
	$conn->close();

	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);