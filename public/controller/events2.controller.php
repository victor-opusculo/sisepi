<?php
//public

use SisEpi\Model\DynamicObject;

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
			$eventInfos = new GenericObjectFromDataRow(getEventBasicInfos($eventId, $conn));

			if (isId($eventInfos->surveyTemplateId))
			{
				$surveyJson = getSurveyTemplateJson($eventInfos->surveyTemplateId, $conn);
			}
			else
				throw new Exception("A pesquisa de satisfação está desabilitada para este evento.");

			if (!empty($_GET['email']))
			{
				
				$studentAlreadyFilledSurvey = false;
				if ((bool)$eventInfos->subscriptionListNeeded && empty($_GET['filled']))
					$studentAlreadyFilledSurvey = checkForExistentAnswer($eventInfos->id, getSubscriptionId($eventInfos->id, $_GET['email'], $conn), null, true, $conn);
				else
					$studentAlreadyFilledSurvey = checkForExistentAnswer($eventInfos->id, null, $_GET['email'], false, $conn);

				if ($studentAlreadyFilledSurvey)
					throw new Exception("Você já respondeu a pesquisa de satisfação para este evento.");
					
				$studentPresencePercent = getStudentPresencePercent($_GET['email'], $eventInfos->id, $eventInfos->subscriptionListNeeded, $conn);

				if (is_null($studentPresencePercent))
					throw new Exception("E-mail não localizado");
				else
				{
					$studentInfos = new DynamicObject();
					$studentInfos->email = $_GET['email'];
					$studentInfos->presencePercent = $studentPresencePercent;
				}
				
				$minPresencePercentRequired = readSetting('STUDENTS_MIN_PRESENCE_PERCENT', $conn);
				if ($studentInfos->presencePercent < $minPresencePercentRequired)
					throw new Exception("Você não atingiu a frequência mínima de {$minPresencePercentRequired}%. A sua presença foi de {$studentInfos->presencePercent}%.");
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
	
}