<?php
//public

use SisEpi\Model\Database\Connection;
use SisEpi\Model\DynamicObject;
use SisEpi\Model\Events\EventCompletedTest;

require_once __DIR__ . '/../../vendor/autoload.php';
final class events2 extends BaseController
{
	public function pre_searchcertificates()
	{
		$this->title = "SisEPI - Procurar certificados";
		$this->subtitle = "Procurar certificados";
	}
	
	public function searchcertificates()
	{
		require_once("model/Database/certificate.database.php");
		require_once("controller/component/DataGrid.class.php");

		$searchResults = null;
		if (!empty($_GET['email']))
		{
			$searchResults = searchCertificates($_GET['email']);
		}

		$dataGridComponent = null;
		$transformDataRowsRules =
		[
			"id" => fn($dr) => $dr['id'],
			"Tipo" => fn($dr) => $dr['eventType'],
			"Evento" => fn($dr) => $dr['name']
		];

		if (!empty($searchResults))
		{
			$dataGridComponent = new DataGridComponent(Data\transformDataRows($searchResults, $transformDataRowsRules));
			$dataGridComponent->columnsToHide[] = "id";
			$dataGridComponent->customButtons['Baixar'] = URL\URLGenerator::generateFileURL("generate/generateCertificate.php", "eventId={eventidparam}&email={emailparam}"); 
			$dataGridComponent->customButtonsParameters['eventidparam'] = 'id';
			$dataGridComponent->customButtonsParameters['emailparam'] = new FixedParameter($_GET['email']);
		}
		else if (!empty($_GET['email']))
			$this->pageMessages[] = "Nenhum certificado encontrado para este e-mail.";
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
	}

	public function pre_fillsurvey()
	{
		$this->title = "SisEPI - Pesquisa de Satisfação";
		$this->subtitle = "Pesquisa de Satisfação";
	}

	public function fillsurvey()
	{
		require_once("model/Database/eventsurveys.database.php");
		//require_once("model/Database/generalsettings.database.php");
		require_once("model/GenericObjectFromDataRow.class.php");

		$eventId = isset($_GET['eventId']) && isId($_GET['eventId']) ? $_GET['eventId'] : null;
		$conn = createConnectionAsEditor();

		$eventInfos = null;
		$surveyJson = null;
		$studentInfos = null;

		try
		{
			$eventGetter = new \SisEpi\Pub\Model\Events\Event();
			$eventGetter->id = $eventId;
			$eventInfos = $eventGetter->getSingle($conn);

			if (isId($eventInfos->surveyTemplateId))
				$surveyJson = getSurveyTemplateJson($eventInfos->surveyTemplateId, $conn);
			/*
			else
				throw new Exception("A pesquisa de satisfação está desabilitada para este evento.");*/

			if (!empty($_GET['email']))
			{
				$eventGetter = new \SisEpi\Model\Events\Event();
				$eventGetter->id = $eventId;
				$privateEventInfos = $eventGetter->getSingle($conn);

				$studentInfos = new DynamicObject();
				$studentInfos->email = $_GET['email'];

				if (empty($_GET['filled']))
					\SisEpi\Pub\Model\Events\CheckPreRequisitesForCertificate::trySurvey($_GET['email'], $conn, $privateEventInfos, true);
			}			
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
			$studentInfos = null;
		}
		finally { $conn->close(); }

		$this->view_PageData['eventInfos'] = $eventInfos;
		$this->view_PageData['surveyObject'] = json_decode($surveyJson);
		$this->view_PageData['studentInfos'] = $studentInfos;
	}

	public function pre_filltest()
	{
		$this->title = "SisEPI - Questionário de avaliação";
		$this->subtitle = "Questionário de avaliação";
	}

	public function filltest()
	{
		//require_once "model/Database/generalsettings.database.php";

		$eventId = isset($_GET['eventId']) && Connection::isId($_GET['eventId']) ? $_GET['eventId'] : null;

		$conn = Connection::get();

		$template = null;
		$studentInfos = null;
		$event = null;
		try
		{
			$publicEventGetter = new \SisEpi\Pub\Model\Events\Event();
			$publicEventGetter->id = $eventId;
			$event = $publicEventGetter->getSingle($conn);

			$eventGetter = new \SisEpi\Model\Events\Event();
			$eventGetter->id = $eventId;
			$eventPrivate = $eventGetter->getSingle($conn);

			if (!Connection::isId($event->testTemplateId))
				throw new Exception("Este evento não tem questionário de avaliação.");

			$templateGetter = new \SisEpi\Model\Events\EventTestTemplate();
			$templateGetter->id = $event->testTemplateId;
			if (!$templateGetter->exists($conn))
				throw new Exception("Modelo de questionário de avaliação não existente.");

			if (!empty($_GET['email']))
			{
				$studentInfos = new DynamicObject();
				$studentInfos->email = $_GET['email'];

				if (empty($_GET['filled']))
					\SisEpi\Pub\Model\Events\CheckPreRequisitesForCertificate::tryTest($_GET['email'], $conn, $eventPrivate, true);

				$template = $templateGetter->getSingle($conn);
			}

		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }

		$this->view_PageData['templateObj'] = $template;
		$this->view_PageData['studentInfos'] = $studentInfos;
		$this->view_PageData['eventObj'] = $event;
	}
	
}