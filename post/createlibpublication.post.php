<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/librarycollection.database.php");

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("LIBR", 9))
{
	$messages = [];

	$createPubResult = createPublication($_POST);
	if($createPubResult['isCreated'])
	{
		$messages[] = "Cadastro de publicação criado com sucesso!";
		writeLog("Biblioteca: Publicação criada. id: " . $createPubResult['newId']);
	}
	else
	{
		$messages[] = "Erro: Cadastro não criado.";
		writeErrorLog("Biblioteca: Ao criar publicação!");
	}
		
	$queryMessages = implode("//", $messages);
	
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);