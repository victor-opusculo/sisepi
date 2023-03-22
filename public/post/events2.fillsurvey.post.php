<?php
require_once("../model/database/eventsurveys.database.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once "../../model/notifications/classes/EventSurveySentNotification.php";

if (isset($_POST["btnsubmitSubmitSurvey"]))
{
	$messages = [];
	
    $conn = createConnectionAsEditor();
	$filled = false;
	try
	{
		$insertResult = saveSurveyAnswer($_POST, $_POST['studentEmail'], $_POST['eventId'], $conn);
		if ($insertResult['isCreated'])
		{
			$filled = true;
			$messages[] = "Obrigado por responder a pesquisa!";
			writeLog("Pesquisa de satisfação enviada. id: " . $insertResult['newId']);

			$notification = new \SisEpi\Model\Notifications\Classes\EventSurveySentNotification
			([
				'eventId' => $insertResult['eventInfos']->id,
				'eventName' => $insertResult['eventInfos']->name,
				'surveyId' => $insertResult['newId'],
				'surveyData' => $insertResult['surveyData']
			]);
			$notification->push($conn);
		}
		else
			throw new Exception("Não foi possível enviar a pesquisa.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Ao enviar pesquisa de satisfação: {$e->getMessage()}. Evento id: " . $_POST['eventId'] . ". E-mail: " . $_POST['studentEmail']);
	}
    finally { $conn->close(); }
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, 
	[ 'eventId' => $_GET['eventId'], 'email' => $_POST['studentEmail'], 'messages' => $messagesString, 'filled' => (int)$filled, 'backToGenCertificate' => $_GET['backToGenCertificate'] ?? 0 ] ), true, 303);
}