<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/Database/libraryborrowedpubs.database.php");

if(isset($_POST["btnsubmitCreateBPub"]) && checkUserPermission("LIBR", 13))
{
	$messages = [];
	$newId = null;
	try
	{
		$createInfos = createLoan($_POST["numPubId"], $_POST["numUserId"], $_POST["hidBorrowDatetime"], $_POST["dateReturnDate"] . " " . $_POST["timeReturnTime"],
		isset($_POST["chkSkipReservations"]) ? true : false );
		
		if($createInfos["isCreated"])
		{
			$newId = $createInfos["newId"];
			$messages[] = "Empréstimo criado com sucesso!";
			writeLog("Biblioteca: Empréstimo criado. id: " . $newId);
		}
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Biblioteca: Ao criar empréstimo! " . $e->getMessage());
	}
	
	$queryNewId = $newId ? "newId=$newId" : ""; 
	$queryMessages = implode("//", $messages);
	
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "$queryNewId&messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);
