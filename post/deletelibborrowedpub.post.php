<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/libraryborrowedpubs.database.php");

if(isset($_POST["btnsubmitFinalizeLoan"]) && checkUserPermission("LIBR", 11))
{
	$messages = [];
	if(finalizeLoan($_POST["bpubId"], $_POST["finalizeOnDate"]))
	{
		$messages[] = "Empréstimo finalizado com sucesso!";
		writeLog("Biblioteca: Empréstimo finalizado. id: " . $_POST['bpubId']);
	}
	else
	{
		$messages[] = "Erro: empréstimo não finalizado.";
		writeErrorLog("Biblioteca: Ao finalizar empréstimo. id: " . $_POST['bpubId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages&receipt=1"), true, 303);
}
else
	die(noPermissionMessage);
