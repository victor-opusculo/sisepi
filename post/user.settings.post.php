<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/user.settings.database.php");

if (isset($_POST["btnsubmitChangeUserData"]))
{
	$messages = [];
	
	try
	{
		if(updateUserPassword($_SESSION['username'], $_POST["txtCurrentPassword"], $_POST["txtNewPassword"]))
		{
			$_SESSION['passwordhash'] = hash('sha256', $_POST["txtNewPassword"]);
			$messages[] = "Senha atualizada!";
			writeLog("Senha de usuário alterada.");
		}
		else
			$messages[] = "Senha não atualizada.";
		
		if (updateUserName($_SESSION['username'], $_POST["txtNewUserName"]))
		{
			writeLog("Nome de usuário alterado. " . $_SESSION['username'] . " => " . $_POST["txtNewUserName"]);
			$_SESSION['username'] = $_POST["txtNewUserName"];
			$messages[] = "Nome de usuário atualizado!";
		}
		else
			$messages[] = "Nome de usuário não atualizado.";
	}
	catch(Exception $ex)
	{
		$messages[] = $ex->getMessage();
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$queryMessages"), true, 303);
}

//********************************************************************************************************************

if (isset($_POST["btnsubmitChangeOtherUser"]) && checkUserPermission("USERS", 1))
{
	$messages = [];
	
	if (updateOtherUserFullData($_POST))
	{
		$messages[] = "Dados de usuário atualizados!";
		writeLog("Dados de usuários atualizados.");
	}
	else
		$messages[] = "Dados não alterados.";
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$queryMessages"), true, 303);
}

//********************************************************************************************************************

if (isset($_POST["frmNewUser"]) && checkUserPermission("USERS", 1))
{
	$messages = [];
	if (createNewUser($_POST))
	{
		$messages[] = "Usuário criado com sucesso!";
		writeLog("Usuário criado: " . $_POST['newUserName']);
	}
	else
	{
		$messages[] = "Erro: Usuário não criado.";
		writeErrorLog("Usuário novo não criado.");
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$queryMessages"), true, 303);
}

//********************************************************************************************************************

if (isset($_POST["frmDelUser"]) && checkUserPermission("USERS", 1))
{
	$messages = [];
	if (deleteUser($_POST["delUserId"]))
	{
		$messages[] = "Usuário excluído com sucesso!";
		writeLog("Usuário excluído. id: " . $_POST["delUserId"]);
	}
	else
	{
		$messages[] = "Erro: Usuário não excluído.";
		writeErrorLog("Usuário não excluído. id: " . $_POST["delUserId"]);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$queryMessages"), true, 303);
}