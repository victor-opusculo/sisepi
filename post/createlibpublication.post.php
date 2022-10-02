<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/librarycollection.database.php");
require_once '../model/librarycollection/Publication.php';

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("LIBR", 9))
{
	$messages = [];

	$pubObject = new \Model\LibraryCollection\Publication();
	$pubObject->fillPropertiesFromFormInput($_POST);

	$conn = createConnectionAsEditor();
	$createPubResult = $pubObject->save($conn);
	if(isset($createPubResult['newId']))
	{
		$messages[] = "Cadastro de publicação criado com sucesso!";
		writeLog("Biblioteca: Publicação criada. id: " . $createPubResult['newId']);
	}
	else
	{
		$messages[] = "Erro: Cadastro não criado.";
		writeErrorLog("Biblioteca: Ao criar publicação!");
	}
	$conn->close();
		
	$queryMessages = implode("//", $messages);
	
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);