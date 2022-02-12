<?php
require_once("model/database/events.database.php");
require_once("model/Event.EventDate.EventAttachment.class.php");

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
		
		$paginatorComponent = new PaginatorComponent(getEventsCount(($_GET["q"] ?? ""), $conn), 20);

		$events = getEventsPartially($paginatorComponent->pageNum, 
											$paginatorComponent->numResultsOnPage, 
											($_GET["orderBy"] ?? ""), 
											($_GET["q"] ?? ""),
											$conn);

		$outputDataRows = Data\transformDataRows($events, 
		[
			'id' => fn($r) => $r['id'],
			'Nome' => fn($r) => $r['name'],
			'Tipo' => fn($r) => $r['typeName'],
			'Data de início' => fn($r) => date_format(date_create($r["date"]), "d/m/Y")
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
		$eventId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;
		
		$eventObject = null;
		try
		{
			$eventObject = new Event($eventId);
		}
		catch (Exception $e)
		{
			$eventObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$this->view_PageData['eventObj'] = $eventObject;
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
		if (empty($_GET["messages"]))
		{
			$this->action = "edit";
			
			$conn = createConnectionAsEditor();
		
			$eventTypes = getEventTypes($conn);
			$professors = getProfessors($conn);
			
			$conn->close();
		
			$eventObject = null;
			try
			{
				$eventObject = new Event("new");
			}
			catch (Exception $e)
			{
				$eventObject = null;
				$this->pageMessages[] = $e->getMessage();
			}
			
			$this->view_PageData['operation'] = "create";
			$this->view_PageData['eventObj'] = $eventObject;
			$this->view_PageData['eventTypes'] = $eventTypes;
			$this->view_PageData['professors'] = $professors;
			
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
		$eventId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;
		
		$eventObject = null;
		try
		{
			$eventObject = new Event($eventId);
		}
		catch (Exception $e)
		{
			$eventObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn = createConnectionAsEditor();
		$eventTypes = getEventTypes($conn);
		$professors = getProfessors($conn);
		$conn->close();
		
		$this->view_PageData['operation'] = "edit";
		$this->view_PageData['eventObj'] = $eventObject;
		$this->view_PageData['eventTypes'] = $eventTypes;
		$this->view_PageData['professors'] = $professors;
		
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
		
		$eventDataRow = null;
		if (!isset($_GET["message"])) $eventDataRow = getSingleEvent($eventId);
		
		if ($eventDataRow === null && !isset($_GET["message"]))
			$this->pageMessages[] = "Registro não localizado.";
		
		$this->view_PageData['eventDataRow'] = $eventDataRow;
	}
}