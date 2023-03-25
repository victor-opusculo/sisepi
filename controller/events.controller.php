<?php
require_once("model/Database/events.database.php");
require_once "vendor/autoload.php";

final class events extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Eventos";
		$this->subtitle = "Eventos";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 4;
	}
	
	public function home()
	{	
		require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");

		$conn = createConnectionAsEditor();
		
		$getter = new \SisEpi\Model\Events\Event();
		$paginatorComponent = new PaginatorComponent($getter->getCount($conn, $_GET['q'] ?? ''), 20);

		$events = $getter->getMultiplePartially($conn, 
												$paginatorComponent->pageNum,
												$paginatorComponent->numResultsOnPage,
												$_GET['orderBy'] ?? '',
												$_GET['q'] ?? '');

		$outputDataRows = Data\transformDataRows($events, 
		[
			'id' => fn($r) => $r->id,
			'Nome' => fn($r) => $r->name,
			'Tipo' => fn($r) => $r->getOtherProperties()->typeName,
			'Modalidade' => fn($r) => Data\getEventMode($r->getOtherProperties()->locTypes),
			'Data de início' => fn($r) => date_format(date_create($r->getOtherProperties()->date), "d/m/Y")
		]);
		
		$dataGridComponent = new DataGridComponent($outputDataRows);
		$dataGridComponent->columnsToHide[] = "id";
		$dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("events", "view", "{param}");
		$dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL("events", "edit", "{param}");
		$dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("events", "delete", "{param}");
		
		$conn->close();
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
	}
	
	public function pre_view()
	{
		$this->title = "SisEPI - Ver evento";
		$this->subtitle = "Ver evento";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 4;
	}
	
	public function view()
	{
		require_once("controller/component/Tabs.class.php");
		require_once("controller/eventsworkplan.controller.php");
		require_once("controller/eventchecklists.controller.php");
		require_once("model/Database/eventlocations.database.php");

		$eventId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
		
		$conn = createConnectionAsEditor();

		$eventObject = null;
		$workplanPage = new eventsworkplan("view", [ 'eventId' => $eventId ]);
		$eventLocations = getAllLocations($conn);
		$checklistPage = null;
		try
		{
			$getter = new \SisEpi\Model\Events\Event();
			$getter->id = $eventId;
			$eventObject = $getter->getSingle($conn);
			$eventObject->setCryptKey(getCryptoKey());
			$eventObject->fetchSubEntities($conn, true);
			$tabsComponent = new TabsComponent("tabsComponent");
			$checklistPage = new eventchecklists("view", [ 'id' => $eventObject->checklistId, 'conn' => $conn ]);
		}
		catch (Exception $e)
		{
			$eventObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }

		$this->view_PageData['eventObj'] = $eventObject;
		$workplanPage->inheritViewPageData($this->view_PageData);
		$this->view_PageData['tabsComp'] = $tabsComponent;
		$this->view_PageData['eventLocations'] = $eventLocations;
		$this->view_PageData['workplanPage'] = $workplanPage;
		$this->view_PageData['checklistPage'] = $checklistPage;
	}
	
	public function pre_create()
	{
		$this->title = "SisEPI - Criar evento";
		$this->subtitle = "Criar evento";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 1;
	}
	
	public function create()
	{
		require_once("controller/component/Tabs.class.php");
		require_once("controller/eventsworkplan.controller.php");
		require_once("controller/eventchecklists.controller.php");
		require_once("model/Database/eventchecklists.database.php");
		require_once("model/Database/eventlocations.database.php");
		require_once("model/Database/eventsurveys.database.php");


		if (empty($_GET["messages"]))
		{
			$this->action = "edit";
			
			$eventObject = new \SisEpi\Model\Events\Event();
			$tabsComponent = null;
			$workplanPage = new eventsworkplan("edit");
			$checklistTemplatesAvailable = null;
			try
			{
				$eventObject->fillPropertiesWithDefaultValues();
				$tabsComponent = new TabsComponent("tabsComponent");
			}
			catch (Exception $e)
			{
				$eventObject = null;
				$this->pageMessages[] = $e->getMessage();
			}

			$conn = createConnectionAsEditor();
			$eventTypes = $eventObject->getTypes($conn);

			$profGetter = new \SisEpi\Model\Professors\Professor();
			$profGetter->setCryptKey(getCryptoKey());
			$professors = $profGetter->getAllBasic($conn);

			$surveyTemplatesAvailable = getAllSurveyTemplates($conn);
			$checklistTemplatesAvailable = getAllEventChecklistTemplates($conn);
			$eventLocations = getAllLocations($conn);
			$subscriptionTemplatesAvailable = getSubscriptionTemplatesNamesAndIds($conn);
			$eventchecklistEditPage = new eventchecklists("edit", [ 'id' => $eventObject->checklistId ?? null, 'conn' => $conn ]);
			$conn->close();
		
			$this->view_PageData['operation'] = "create";
			$this->view_PageData['eventObj'] = $eventObject;
			$workplanPage->inheritViewPageData($this->view_PageData);
			$this->view_PageData['tabsComp'] = $tabsComponent;
			$this->view_PageData['workplanPage'] = $workplanPage;
			$this->view_PageData['eventLocations'] = $eventLocations;
			$this->view_PageData['eventTypes'] = $eventTypes;
			$this->view_PageData['professors'] = $professors;
			$this->view_PageData['subscriptionTemplatesAvailable'] = $subscriptionTemplatesAvailable;
			$this->view_PageData['surveyTemplatesAvailable'] = $surveyTemplatesAvailable;
			$this->view_PageData['checklistTemplatesAvailable'] = $checklistTemplatesAvailable;
			$this->view_PageData['eventchecklistEditPage'] = $eventchecklistEditPage;
		}
		else
		{
			$this->action = "create_sent";
		}
	}
	
	public function pre_edit()
	{
		$this->title = "SisEPI - Editar evento";
		$this->subtitle = "Editar evento";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 2;
	}
	
	public function edit()
	{
		require_once("controller/component/Tabs.class.php");
		require_once("controller/eventsworkplan.controller.php");
		require_once("controller/eventchecklists.controller.php");
		require_once("model/Database/eventchecklists.database.php");
		require_once("model/Database/eventsurveys.database.php");
		require_once("model/Database/eventlocations.database.php");

		$eventId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;
		
		$eventObject = null;
		$tabsComponent = null;
		$workplanPage = new eventsworkplan("edit");
		$checklistTemplatesAvailable = null;

		$conn = createConnectionAsEditor();

		$eventGetter = new \SisEpi\Model\Events\Event();
		$profGetter = new \SisEpi\Model\Professors\Professor();
		$profGetter->setCryptKey(getCryptoKey());
		try
		{
			$eventGetter->id = $eventId;
			$eventObject = $eventGetter->getSingle($conn);
			$eventObject->fetchSubEntities($conn, true);
			$tabsComponent = new TabsComponent("tabsComponent");
		}
		catch (Exception $e)
		{
			$eventObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$eventTypes = $eventGetter->getTypes($conn);
		$professors = $profGetter->getAllBasic($conn);
		$checklistTemplatesAvailable = getAllEventChecklistTemplates($conn);
		$surveyTemplatesAvailable = getAllSurveyTemplates($conn);
		$eventLocations = getAllLocations($conn);
		$subscriptionTemplatesAvailable = getSubscriptionTemplatesNamesAndIds($conn);
		$eventchecklistEditPage = new eventchecklists("edit", [ 'id' => $eventObject->checklistId ?? null, 'conn' => $conn ]);
		
		$conn->close();
		
		$this->view_PageData['operation'] = "edit";
		$this->view_PageData['eventObj'] = $eventObject;
		$workplanPage->inheritViewPageData($this->view_PageData);
		$this->view_PageData['tabsComp'] = $tabsComponent;
		$this->view_PageData['workplanPage'] = $workplanPage;
		$this->view_PageData['eventLocations'] = $eventLocations;
		$this->view_PageData['eventTypes'] = $eventTypes;
		$this->view_PageData['professors'] = $professors;
		$this->view_PageData['checklistTemplatesAvailable'] = $checklistTemplatesAvailable;
		$this->view_PageData['surveyTemplatesAvailable'] = $surveyTemplatesAvailable;
		$this->view_PageData['subscriptionTemplatesAvailable'] = $subscriptionTemplatesAvailable;
		$this->view_PageData['eventchecklistEditPage'] = $eventchecklistEditPage;
		
	}
	
	public function pre_delete()
	{
		$this->title = "SisEPI - Excluir evento";
		$this->subtitle = "Excluir evento";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 3;
	}
	
	public function delete()
	{
		$eventId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;
		
		$eventObject = null;
		$eventGetter = new \SisEpi\Model\Events\Event();
		$eventGetter->id = $eventId;

		$conn = createConnectionAsEditor();
		try
		{
			if (!isset($_GET["message"])) $eventObject = $eventGetter->getSingle($conn);
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }
		
		$this->view_PageData['eventObj'] = $eventObject;
	}
}