<?php
require_once("checkLogin.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../model/database/calendar.database.php");
require_once("../model/DatabaseEntity.php");

if(isset($_POST["btnsubmitSubmitDate"]) && checkUserPermission("CALEN", 4))
{
	$messages = [];
	try
	{
		$dbEntityFromPOST = new DatabaseEntity("calendardate", $_POST);

		$extraDatesChangesReportObj = json_decode($dbEntityFromPOST->attachedData['extra:extraDatesChangesReport'], true);
		$dbEntityFromPOST->attachedData['dbEntitiesExtraDatesChangesReport'] = [ 'create' => [], 'update' => [], 'delete' => [] ];

		foreach ($extraDatesChangesReportObj['create'] as $createReg)
			$dbEntityFromPOST->attachedData['dbEntitiesExtraDatesChangesReport']['create'][] = new DatabaseEntity('calendardate', $createReg);

		foreach ($extraDatesChangesReportObj['update'] as $updateReg)
			$dbEntityFromPOST->attachedData['dbEntitiesExtraDatesChangesReport']['update'][] = new DatabaseEntity('calendardate', $updateReg);

		foreach ($extraDatesChangesReportObj['delete'] as $deleteReg)
			$dbEntityFromPOST->attachedData['dbEntitiesExtraDatesChangesReport']['delete'][] = new DatabaseEntity('calendardate', $deleteReg);

		$updateEventResult =  updateFullCalendarDates($dbEntityFromPOST);

		if($updateEventResult > 0)
		{
			$messages[] = "Data/evento editado com sucesso!";
			writeLog("Agenda: Data/evento editado. id: " . $_POST['calendardates:calendarEventId']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Agenda: Ao editar data/evento simples! " . $e->getMessage());
	}
	
	$messagesQuery = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET["cont"], $_GET["action"], $_GET['id'], "messages=$messagesQuery"), true, 303);
}
else
	die(noPermissionMessage);