<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/artmuseum.database.php");

if(isset($_POST["btnsubmitSubmit"]) && checkUserPermission("ARTM", 3))
{
	$messages = [];
	
	try
	{
		if(updateFullArtPiece($_POST, $_FILES))
		{
			$messages[] = "Arte modificada com sucesso!";
			writeLog("Obra de arte modificada. id: " . $_POST['artPieceId']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (FileUploadException $e)
	{
		$messages[] = "Obra de arte alterada, porém com erro.";
		$messages[] = $e->getMessage();
		writeErrorLog("Obra de arte modificada com erro. " . $e->getMessage() . " id: " . $_POST['artPieceId']);
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeLog("Obra de arte não modificada. id: " . $_POST['artPieceId']);
	}
	
	$queryMessages = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET["id"] ?? 0, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);