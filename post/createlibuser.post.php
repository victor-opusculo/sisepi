<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/libraryusers.database.php");

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("LIBR", 8))
{
	$messages = [];
	$newId = null;
	
	$createdUserInfos = createUser($_POST);
	if($createdUserInfos["affectedRows"] > 0)
	{
		$messages[] = "Usuário criado com sucesso!";
		$newId = $createdUserInfos["newId"];
		writeLog("Biblioteca: Usuário criado. id: " . $newId);
	}
	else
	{
		$messages[] = "Erro: Usuário não criado.";
		writeErrorLog("Biblioteca: Ao criar usuário! Não criado.");
	}
	
	$queryNewId = $newId ? "newId=$newId" : "";
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "$queryNewId&messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);