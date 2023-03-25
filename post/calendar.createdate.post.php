<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once "../vendor/autoload.php";

use \SisEpi\Model\Calendar\CalendarDate;
use \SisEpi\Model\Database\Connection;

if(isset($_POST["btnsubmitSubmitDate"]) && checkUserPermission("CALEN", 3))
{
	$messages = [];
	$conn = Connection::create();
	try
	{
		$newDate = new CalendarDate();
		$newDate->fillPropertiesFromFormInput($_POST);
		$createEventResult = $newDate->save($conn);
/*
		$dbEntityFromPOST = new DatabaseEntity("calendardate", $_POST);
		$extraDatesChangesReportObj = json_decode($dbEntityFromPOST->attachedData['extra:extraDatesChangesReport'], true);
		$dbEntityFromPOST->attachedData['dbEntitiesExtraDatesChangesReport'] = [ 'create' => [], 'update' => [], 'delete' => [] ];

		foreach ($extraDatesChangesReportObj['create'] as $createReg)
			$dbEntityFromPOST->attachedData['dbEntitiesExtraDatesChangesReport']['create'][] = new DatabaseEntity('calendardate', $createReg);

		foreach ($extraDatesChangesReportObj['update'] as $updateReg)
			$dbEntityFromPOST->attachedData['dbEntitiesExtraDatesChangesReport']['update'][] = new DatabaseEntity('calendardate', $updateReg);

		foreach ($extraDatesChangesReportObj['delete'] as $deleteReg)
			$dbEntityFromPOST->attachedData['dbEntitiesExtraDatesChangesReport']['delete'][] = new DatabaseEntity('calendardate', $deleteReg);

		$createEventResult =  createFullCalendarDates($dbEntityFromPOST);*/

		if($createEventResult['affectedRows'] > 0)
		{
			$messages[] = "Data/evento criado com sucesso!";
			writeLog("Agenda: Data/evento criado. id: " . $createEventResult['newId']);
		}
		else
			throw new Exception("Erro: data/evento simples não criado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Agenda: Ao criar data/evento simples! " . $e->getMessage());
	}
	finally { $conn->close(); }
	
	$messagesQuery = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], null, "messages=$messagesQuery"), true, 303);
}
else
	die(noPermissionMessage);