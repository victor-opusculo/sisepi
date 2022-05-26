<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/eventlocations.database.php");
require_once("../model/DatabaseEntity.php");

if(isset($_POST["btnsubmitSubmitLocations"]) && checkUserPermission("EVENT", 13))
{
	$messages = [];
	try
	{
		$changesReport = json_decode($_POST['extra:locationChangesReport']);

		$dbEntitiesChangesReport = 
		[
			"create" => array_map( fn($obj) => new DatabaseEntity('eventlocation', $obj), $changesReport->create ),
			"update" => array_map( fn($obj) => new DatabaseEntity('eventlocation', $obj), $changesReport->update ),
			"delete" => array_map( fn($obj) => new DatabaseEntity('eventlocation', $obj), $changesReport->delete )
		];
		
		if(executeDbEntitiesChangesReport($dbEntitiesChangesReport) > 0)
		{
			$messages[] = "Locais editados com sucesso!";
			writeLog("Configurações: Locais de eventos alterados");
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Configurações: Ao editar locais de eventos! " . $e->getMessage());
	}
	
	$messagesQuery = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET['id'], "messages=$messagesQuery"), true, 303);
}
else
	die(noPermissionMessage);