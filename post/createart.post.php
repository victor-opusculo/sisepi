<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/artmuseum.database.php");

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("ARTM", 2))
{
	$messages = [];
	
	try
	{
		$createArtResult = createFullArt($_POST, $_FILES);
		if($createArtResult["isCreated"])
		{
			$messages[] = "Cadastro de obra de arte criado com sucesso!";
			writeLog("Obra de arte criada - id: " . $createArtResult['newId']);
		}
		else
		{
			throw new Exception("Erro: Cadastro não criado.");
		}
	}
	catch (FileUploadException $e)
	{
		$messages[] = "Obra de arte criada, porém com erro.";
		writeErrorLog("Obra de arte criada com erro. id: " . ($e->artPieceId ?? "") . ". " . $e->getMessage());
		$messages[] = $e->getMessage();
	}
	catch (Exception $e)
	{
		writeErrorLog("Ao criar obra de arte! " . $e->getMessage());
		$messages[] = $e->getMessage();
	}
	
	$queryMessages = implode("//", $messages);
	
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);
