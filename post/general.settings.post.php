<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/generalsettings.database.php");

function startsWith($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

if (isset($_POST["btnsubmitChangeGeneralSettings"]))
{
	$messages = [];
	$affectedRows = 0;
	$conn = createConnectionAsEditor();
	foreach ($_POST as $key => $value)
	{
		if (startsWith($key, "sett_"))
		{
			$settingName = substr($key, 5);
			$affectedRows += updateSetting($settingName, $value, $conn);
		}
	}
	$conn->close();
	
	if($affectedRows > 0)
	{
		$messages[] = "Configurações gerais alteradas!";
		writeLog("Configurações: Configurações gerais alteradas.");
	}
	else
	{
		$messages[] = "Nenhuma configuração alterada.";
		writeLog("Configurações: Configurações gerais não alteradas.");
	}
	
	$queryMessages = implode("//", $messages);
	
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$queryMessages"), true, 303);
}
else
	die(noPermissionMessage);