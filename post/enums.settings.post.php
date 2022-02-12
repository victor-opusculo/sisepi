<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/enums.settings.database.php");

if (isset($_POST["btnsubmitEditEnums"]) && 
(checkUserPermission("ENUM", 1) || checkUserPermission("ENUM", 2) || checkUserPermission("ENUM", 3)))
{
	$messages = "";
	if(applyEnumChangesReport($_POST["hiddenJsonChangesReport"]))
	{
		$messages = "Enumeradores atualizados!";
		writeLog("Configurações: Enumeradores atualizados.");
	}
	
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$messages"), true, 303);
}
else
	die(noPermissionMessage);