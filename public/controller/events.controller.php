<?php
//public

require_once __DIR__ . '/../model/events/Event.php';

final class events extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Eventos";
		$this->subtitle = "Eventos";
	}
	
	public function home()
	{
		require_once("model/database/database.php");
		require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");

		$getter = new \Model\Events\Event();

		$paginatorComponent = null;
		$dataGridComponent = null;

		$conn = createConnectionAsEditor();
		try
		{
			$paginatorComponent = new PaginatorComponent($getter->getCount($conn, $_GET['q'] ?? ''), 20);
			$events = $getter->getMultiplePartially($conn, 
													$paginatorComponent->pageNum, 
													$paginatorComponent->numResultsOnPage,
													$_GET['orderBy'] ?? '',
													$_GET['q'] ?? '');

			$transformRules =
			[
				'id' => fn($e) => $e->id,
				'Nome' => fn($e) => $e->name,
				'Tipo' => fn($e) => $e->getOtherProperties()->typeName,
				'Modalidade' => fn($e) => Data\getEventMode($e->getOtherProperties()->locTypes),
				'Data de início' => fn($e) => date_create($e->getOtherProperties()->date)->format('d/m/Y'),
				'Carga horária' => fn($e) => round(timeStampToHours($e->getOtherProperties()->hours), 1) . 'h'
			];

			$dataGridComponent = new DataGridComponent(Data\transformDataRows($events, $transformRules));
			$dataGridComponent->columnsToHide[] = "id";
			$dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("events", "view", "{param}"); 
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
	}
	
	public function pre_view()
	{
		$this->title = "SisEPI - Ver evento";
		$this->subtitle = "Ver evento";
	}
	
	public function view()
	{	
		$eventId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
		$eventObject = null;

		$conn = createConnectionAsEditor();
		try
		{
			$getter = new \Model\Events\Event();
			$getter->id = $eventId;
			$eventObject = $getter->getSingle($conn);
			$eventObject->setCryptKey(getCryptoKey());
			$eventObject->fetchSubEntities($conn, true);
		}
		catch (Exception $e)
		{
			$eventObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }
		
		$isSubscriptionListFull = function() use ($eventObject)
		{
			if ($eventObject === null) return;
			return ($eventObject->currentSubscriptionNumber >= $eventObject->maxSubscriptionNumber); 
		};
		
		$passedSubscriptionClosureDate = function() use ($eventObject)
		{
			if ($eventObject === null) {  return; }
			
			$today = date("Y-m-d");
			$closureDate = $eventObject->subscriptionListClosureDate;
			
			$dt_today = new DateTime($today);
			$dt_closureDate = new DateTime($closureDate);
						
			return $dt_today >= $dt_closureDate;
		};

		$isSubscriptionYetToOpen = function() use ($eventObject)
		{
			if ($eventObject === null) return;

			$today = date("Y-m-d");
			$openingDate = $eventObject->subscriptionListOpeningDate;

			if (empty($openingDate)) return false;

			$dt_today = new DateTime($today);
			$dt_openingDate = new DateTime($openingDate);

			return $dt_today < $dt_openingDate;
		};
		
		$this->view_PageData['eventObj'] = $eventObject;
		$this->view_PageData['passedSubscriptionClosureDate'] = $passedSubscriptionClosureDate();
		$this->view_PageData['isSubscriptionYetToOpen'] = $isSubscriptionYetToOpen();
		$this->view_PageData['isSubscriptionListFull'] = $isSubscriptionListFull();
	}
	
	public function pre_authcertificate()
	{
		$this->title = "SisEPI - Autenticar Certificado";
		$this->subtitle = "Autenticar Certificado";
	}
	
	public function authcertificate()
	{
		require_once("model/database/certificate.database.php");
		
		$showData = false;
		$eventData = null; $certDataRow = null; $studentDataRow = null;
		
		if (isset($_GET["code"]) && isset($_GET["date"]) && isset($_GET["time"]))
		{
			$showData = true;
			$conn = createConnectionAsEditor();
			
			$certDataRow = authenticateCertificate($_GET["code"], $_GET["date"] . " " . $_GET["time"], $conn);
			
			if ($certDataRow)
			{
				$eventData = getSingleEvent($certDataRow["eventId"], $conn);
				$studentDataRow = getStudentData($certDataRow["eventId"], $eventData["subscriptionListNeeded"], $certDataRow["email"], $conn);
			}
			
			$conn->close();
		}
		
		$this->view_PageData['showData'] = $showData;
		$this->view_PageData['certDataRow'] = $certDataRow;
		$this->view_PageData['studentDataRow'] = $studentDataRow;
	}
	
	public function pre_viewsubscriptionlist()
	{
		$this->title = "SisEPI - Ver lista de inscrição";
		$this->subtitle = "Ver lista de inscrição";
	}
	
	public function viewsubscriptionlist()
	{
		require_once("model/database/students.database.php");
		require_once("controller/component/DataGrid.class.php");
		
		$eventId = isset($_GET['eventId']) && is_numeric($_GET['eventId']) ? $_GET['eventId'] : null;
		$listInfosDataRow = null;
		
		$transformDataRowsArray = function() use ($eventId, &$listInfosDataRow)
		{	
			$conn = createConnectionAsEditor();
			$input = getSubscriptionList($eventId, $conn);
			$listInfosDataRow = getEventSubscriptionListInfos($eventId, $conn);
			$conn->close();
			
			$output = [];
			foreach ($input as $row)
			{
				$subscriptionData = json_decode($row['subscriptionDataJson']);
				$socialName = isset($subscriptionData) ? Data\getSubscriptionInfoFromDataObject($subscriptionData, "socialName") : null;
				$newRow = [];
				$newRow["id"] = $row["id"];
				$newRow["eventId"] = $row["eventId"];
				$newRow["Nome"] = $row["name"] . ($socialName ? " (" . $socialName . ")" : "");
				$newRow["Data de inscrição"] = date_format(date_create($row["subscriptionDate"]), "d/m/Y H:i:s");
							
				array_push($output, $newRow);
			}
			
			return $output;
		};
		
		$dataRows = $transformDataRowsArray();
		
		$dataGridComponent = new DataGridComponent($dataRows);
		$dataGridComponent->columnsToHide[] = "id";
		$dataGridComponent->columnsToHide[] = "eventId";
		
		$this->view_PageData['listInfosDataRow'] = $listInfosDataRow;
		$this->view_PageData['dataRows'] = $dataRows;
		$this->view_PageData['dgComp'] = $dataGridComponent;
	}
	
	public function pre_gencertificate()
	{
		$this->title = "SisEPI - Gerar certificado";
		$this->subtitle = "Gerar certificado";
	}
	
	public function gencertificate()
	{
		require_once("model/database/certificate.database.php");
		require_once("model/database/generalsettings.database.php");
		
		$conn = createConnectionAsEditor();
		$eventId = isset($_GET['eventId']) && is_numeric($_GET['eventId']) ? $_GET['eventId'] : null;
		$eventDataRow = getSingleEvent($eventId, $conn);
		$isEventOver = isEventOver($eventId, $conn);
		$minPercentageForApproval = (int)readSetting("STUDENTS_MIN_PRESENCE_PERCENT", $conn);
				
		if (isset($_POST["btnsubmitGenCert"]))
		{
			$studentData = getStudentData($eventId, $eventDataRow["subscriptionListNeeded"], $_POST["txtEmail"], $conn);
			
			if ($studentData)
			{
				$filledSurvey = false;
				if (!empty($eventDataRow['surveyTemplateId']))
				{
					if ($eventDataRow['subscriptionListNeeded'])
						$filledSurvey = checkForExistentSurveyAnswer($eventDataRow['id'], $studentData['subscriptionId'], null, true, $conn);
					else
						$filledSurvey = checkForExistentSurveyAnswer($eventDataRow['id'], null, $studentData['email'], false, $conn);
				}
				else
					$filledSurvey = true;

				if ($studentData["presencePercent"] >= $minPercentageForApproval)
				{
					if ($filledSurvey)
						header("location:" . URL\URLGenerator::generateFileURL('generate/generateCertificate.php', ['eventId' => $eventId, 'email' => $_POST['txtEmail']]), true, 303);
					else
					{
						$pageMessages = 'Preencha a pesquisa de satisfação para obter o seu certificado.';
						header("location:" . URL\URLGenerator::generateSystemURL('events2', 'fillsurvey', null, ['eventId' => $eventId, 'email' => $_POST['txtEmail'], 'messages' => $pageMessages, 'backToGenCertificate' => 1]), true, 303);
					}
				}
				else
					array_push($this->pageMessages, "Você não atingiu a presença mínima de " . $minPercentageForApproval . "%. A sua presença foi de " . $studentData["presencePercent"] . "%.");

			}
			else
			{
				array_push($this->pageMessages, "E-mail não localizado!");
			}
		}
		
		$conn->close();

		$this->view_PageData['eventDataRow'] = $eventDataRow;
		$this->view_PageData['isEventOver'] = $isEventOver;
	}
	
	public function pre_latesubscription()
	{
		$this->title = "SisEPI - Inscrição tardia";
		$this->subtitle = "Inscrição tardia";
	}
	
	public function latesubscription()
	{
		require_once("model/database/students.database.php");
		require_once("model/database/generalsettings.database.php");
		require_once("model/GenericObjectFromDataRow.class.php");

		$eventId = isset($_GET['eventId']) && is_numeric($_GET['eventId']) ? $_GET['eventId'] : 0;;
		$eventObject = null;
		$subscriptionTemplate = null;
		$showForm = null;
	
		$conn = createConnectionAsEditor();
		try
		{
			$eventObject = new GenericObjectFromDataRow(getEventBasicInfos($eventId, $conn));
			$showForm = true;
			
			$subscriptionCount = getSubscriptionsCount($eventId, $conn);
			$subscriptionTemplate = json_decode(getEventsSubscriptionTemplate($eventId, $conn));
			
			if (!(boolean)$eventObject->allowLateSubscriptions)
			{
				$showForm = false;
				$this->pageMessages[] = "Inscrição tardia não permitida para este evento.";
			}
			
			if ($subscriptionCount >= $eventObject->maxSubscriptionNumber)
			{
				$showForm = false;
				$this->pageMessages[] = "Todas as vagas já estão ocupadas.";
			}
		}
		catch(Exception $e)
		{
			$eventObject = null;
			$showForm = false;
			$this->pageMessages[] = "Registro não localizado.";
		}
		$conn->close();
		
		if (!empty($_GET["messages"]))
			$showForm = false;
		
		$this->view_PageData['eventObj'] = $eventObject;
		$this->view_PageData['showForm'] = $showForm;
		$this->view_PageData['subscriptionTemplate'] = $subscriptionTemplate;
	}
	
	public function pre_subscribe()
	{
		$this->title = "SisEPI - Inscrição";
		$this->subtitle = "Inscrever-se";
	}
	
	public function subscribe()
	{
		require_once("model/database/students.database.php");
		require_once("model/database/generalsettings.database.php");
		require_once("model/GenericObjectFromDataRow.class.php");
		
		$eventObj;
		$currentSubscriptionNumber;
		$socialMediaLinks;
		$subscriptionTemplate = null;
		
		$eventId = isset($_GET['eventId']) && is_numeric($_GET['eventId']) ? $_GET['eventId'] : null;
		
		$isValidEventId = function() use (&$eventObj)
		{
			return ($eventObj !== null);
		};
		
		$isListFull = function() use (&$eventObj, &$currentSubscriptionNumber)
		{
			return $eventObj ? ($currentSubscriptionNumber >= $eventObj->maxSubscriptionNumber) : null;
		};
		
		$passedClosureDate = function() use (&$eventObj)
		{
			if ($eventObj === null) return null;
			
			$today = date("Y-m-d");
			$closureDate = $eventObj->subscriptionListClosureDate;

			$today_dt = new DateTime($today);
			$closure_dt = new DateTime($closureDate);
			
			if ($today_dt >= $closure_dt)
				return true;
			
			return false;
		};

		$isSubscriptionYetToOpen = function() use (&$eventObj)
		{
			if ($eventObj === null) return;

			$today = date("Y-m-d");
			$openingDate = $eventObj->subscriptionListOpeningDate;

			if (empty($openingDate)) return false;

			$dt_today = new DateTime($today);
			$dt_openingDate = new DateTime($openingDate);

			return $dt_today < $dt_openingDate;
		};
		
		$conn = createConnectionAsEditor();
		try
		{
			$eventObj = new GenericObjectFromDataRow(getEventBasicInfos($eventId, $conn));
			$currentSubscriptionNumber = getSubscriptionsCount($eventId, $conn);
			$subscriptionTemplate = json_decode(getEventsSubscriptionTemplate($eventId, $conn));
			$socialMediaLinks = readMultipleSettings(['SOCIAL_MEDIA_URL_FACEBOOK',
			'SOCIAL_MEDIA_URL_INSTAGRAM',
			'SOCIAL_MEDIA_URL_LINKEDIN',
			'SOCIAL_MEDIA_URL_TWITTER',
			'SOCIAL_MEDIA_URL_YOUTUBE'], $conn);
		}
		catch (Exception $e)
		{
			$eventObj = null;
			$this->pageMessages[] = "Registro não localizado.";
		}
		
		$this->view_PageData['eventObj'] = $eventObj;
		$this->view_PageData['isListFull'] = $isListFull();
		$this->view_PageData['passedClosureDate'] = $passedClosureDate();
		$this->view_PageData['isSubscriptionYetToOpen'] = $isSubscriptionYetToOpen();
		$this->view_PageData['isValidEventId'] = $isValidEventId();
		$this->view_PageData['subscriptionTemplate'] = $subscriptionTemplate;
		$this->view_PageData['socialMediaLinks'] = $socialMediaLinks;
		$this->view_PageData['connection'] = $conn;
	}
	
	public function pre_signpresencelist()
	{
		$this->title = "SisEPI - Lista de presença";
		$this->subtitle = "Lista de presença";
	}
	
	public function signpresencelist()
	{
		require_once("model/database/students.database.php");
		require_once("model/GenericObjectFromDataRow.class.php");
		
		$eventDateId;
		$eventDateObject;
		$eventInfosObject;	
		$currentSubscriptionNumber;
		$subscriptionListNames;
		$sentPassword;
		
		$eventDateId = isset($_GET['eventDateId']) && is_numeric($_GET['eventDateId']) ? $_GET['eventDateId'] : 0;
		$sentPassword = $_POST["txtListPassword"] ?? "";

		$conn = createConnectionAsEditor();
		try
		{
			$eventDateObject = new GenericObjectFromDataRow(getEventDate($eventDateId, $conn));
			$eventInfosObject = new GenericObjectFromDataRow(getEventBasicInfos($eventDateObject->eventId, $conn));
			$currentSubscriptionNumber = getSubscriptionsCount($eventDateObject->eventId, $conn);
			$subscriptionListNames = getSubscriptionListOnlyNamesAndIds($eventDateObject->eventId, $conn);
		}
		catch (Exception $e)
		{
			$eventDateObject = null;
			$eventInfosObject = null;
			$this->pageMessages[] = "Registro não localizado.";
			$this->pageMessages[] = $e->getMessage();
		}
		$conn->close();
		
		$isSubscriptionListFull = function() use ($eventInfosObject, $currentSubscriptionNumber)
		{
			if ($eventInfosObject === null) return;
			return ($currentSubscriptionNumber >= $eventInfosObject->maxSubscriptionNumber); 
		};
		
		$getOperationMode = function() use ($eventDateObject, $eventInfosObject, $sentPassword)
		{
			if (isset($eventDateObject) && isset($eventInfosObject))
			{
				if (!$eventDateObject->presenceListNeeded)
					return "unnecessary";
				else if (!$eventDateObject->isPresenceListOpen)
					return "closedList";
				else if (!empty($_GET['signed']) && (bool)$_GET['signed'])
					return "postSigned";
				else if ($sentPassword !== $eventDateObject->presenceListPassword)
					return "askPassword";
				else if (!$eventInfosObject->subscriptionListNeeded)
					return "noSubscription";
				else
					return "common";
			}
			else
				return "";	
		};
		
		$this->view_PageData['eventInfos'] = $eventInfosObject;
		$this->view_PageData['eventDateInfos'] = $eventDateObject;
		$this->view_PageData['isSubscriptionListFull'] = $isSubscriptionListFull();
		$this->view_PageData['subscriptionListNames'] = $subscriptionListNames;
		$this->view_PageData['operation'] = $getOperationMode();
	}
}