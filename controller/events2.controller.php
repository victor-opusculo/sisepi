<?php
require_once("model/database/students.database.php");
require_once("model/GenericObjectFromDataRow.class.php");

final class events2 extends BaseController
{
	public function pre_viewsubscriptionlist()
	{
		$this->title = "SisEPI - Ver lista de inscrição";
		$this->subtitle = "Ver lista de inscrição";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 4;
	}
	
	public function viewsubscriptionlist()
	{
		require_once("controller/component/DataGrid.class.php");
		
		$eventId = isset($_GET['eventId']) && is_numeric($_GET['eventId']) ? $_GET['eventId'] : null;
		$listInfosDataRow = null;
		$listCount = null;		
		
		$transformDataRowsArray = function() use ($eventId, &$listInfosDataRow, &$listCount)
		{	
			$conn = createConnectionAsEditor();
		
			$input = getSubscriptionList($eventId, $conn);
			$listInfosDataRow = getEventSubscriptionListInfos($eventId, $conn);
			$listCount = getSubscriptionsCount($eventId, $conn);
			
			$conn->close();
	
			define('disabledPersonIconImageElement', '<img src="' . URL\URLGenerator::generateFileURL("pics/access.jpg") . '"/>');			
			$checkIcon = new DataGridIcon("pics/check.png", "Sim");
			
			$output = Data\transformDataRows($input, 
			[
				'id' => fn($row) => $row['id'],
				'eventId' => fn($row) => $row['eventId'],
				'Nome' => fn($row) => $row["name"] . ($row["socialName"] ? " (" . $row["socialName"] . ")" : ""),
				'E-mail' => fn($row) => $row['email'],
				disabledPersonIconImageElement => fn($row) => $row["accessibilityFeatureNeeded"] === null || $row["accessibilityFeatureNeeded"] === '' ? '' : $checkIcon,
				'Data de inscrição' => fn($row) => date_format(date_create($row["subscriptionDate"]), "d/m/Y H:i:s")
			]);
			
			return $output;
		};
		
		$dataRows = $transformDataRowsArray();
		
		$dataGridComponent = new DataGridComponent($dataRows);
		$dataGridComponent->columnsToHide[] = "id";
		$dataGridComponent->columnsToHide[] = "eventId";
		$dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("events2", "viewsubscription", "{param}");
		$dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("events2", "deletesubscription", "{param}");
		
		$this->view_PageData['listInfosDataRow'] = $listInfosDataRow;
		$this->view_PageData['listCount'] = $listCount;
		$this->view_PageData['dataRows'] = $dataRows;
		$this->view_PageData['dgComp'] = $dataGridComponent;
	}
	
	public function pre_viewsubscription()
	{
		$this->title = "SisEPI - Ver inscrição";
		$this->subtitle = "Ver inscrição";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 4;
	}
	
	public function viewsubscription()
	{
		$subsId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;
				
		$conn = createConnectionAsEditor();	
		
		$subsObj = null;
		$eventInfoDataRow = null;
		try
		{
			$subsObj = new GenericObjectFromDataRow(getSingleSubscription($subsId, $conn));
			$eventInfoDataRow = getEventSubscriptionListInfos($subsObj->eventId, $conn);
		}
		catch (Exception $e)
		{
			$subsObj = null;
			$this->pageMessages[] = $e->getMessage();
		} 
		$conn->close();
		
		$this->view_PageData['subsObj'] = $subsObj;
		$this->view_PageData['eventInfoDataRow'] = $eventInfoDataRow;
	}
	
	public function pre_viewpresencesapp()
	{
		$this->title = "SisEPI - Ver apontamentos";
		$this->subtitle = "Ver apontamentos";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 4;
	}
	
	public function viewpresencesapp()
	{
		require_once("controller/component/DataGrid.class.php");
		
		$eventId = isset($_GET['eventId']) && is_numeric($_GET['eventId']) ? $_GET['eventId'] : null;
		
		$eventInfos = null;
		
		$transformDataRowsArray = function() use ($eventId, &$eventInfos)
		{
			$approvedOnly = (isset($_GET["approvedOnly"]) && (int)$_GET["approvedOnly"]) ? true : false;
			
			$conn = createConnectionAsEditor();
		
			$eventInfos = getEventSubscriptionListInfos($eventId, $conn);
			$input = $eventInfos["subscriptionListNeeded"] ? 
								getPresenceAppointment($eventId, $approvedOnly, $conn) : 
								getPresenceAppointmentNoSubs($eventId, $approvedOnly, $conn);
			
			$conn->close();
			
			$output = Data\transformDataRows($input,
			[
				'subscriptionId' => fn($row) => $row["subscriptionId"] ?? "",
				'Nome' => fn($row) => $row["name"] . (isset($row["socialName"]) && $row["socialName"] ? " (" . $row["socialName"] . ")" : ""),
				'E-mail' => fn($row) => $row["email"],
				'Presença' => fn($row) => $row["presencePercent"] . "%"
			]);

			return $output;
		};
		
		$dataRows = $transformDataRowsArray();
		
		$dataGridComponent = new DataGridComponent($dataRows);
		$dataGridComponent->columnsToHide[] = "subscriptionId";
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['eventInfos'] = $eventInfos;
	}
	
	public function pre_viewpresencelist()
	{
		$this->title = "SisEPI - Ver lista de presença";
		$this->subtitle = "Lista de presença";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 4;
	}
	
	public function viewpresencelist()
	{
		require_once("controller/component/DataGrid.class.php");
		
		$eventDateId = isset($_GET['eventDateId']) && is_numeric($_GET['eventDateId']) ? $_GET['eventDateId'] : null;
		
		$conn = createConnectionAsEditor();
		
		$eventDateObject = null; $eventInfosObject = null; $dataGridComponent = null; $presenceCount = 0;
		
		try
		{
			$eventDateObject = new GenericObjectFromDataRow(getEventDate($eventDateId, $conn));
			$eventInfosObject = new GenericObjectFromDataRow(getEventBasicInfos($eventDateObject->eventId, $conn));

			$inputDataRows = $eventInfosObject->subscriptionListNeeded ? getPresenceList($eventDateId, $conn) : getPresenceListNoSubs($eventDateId, $conn);
			$outputDataRows = Data\transformDataRows($inputDataRows,
			[
				'id' => fn($row) => $row['id'],
				'Nome' => fn($row) => $row["name"] . (isset($row["socialName"]) && $row["socialName"] ? " (" . $row["socialName"] . ")" : ""),
				'E-mail' => fn($row) => $row["email"]
			]);

			$presenceCount = count($outputDataRows);

			$dataGridComponent = new DataGridComponent($outputDataRows);
			$dataGridComponent->columnsToHide[] = "id";
			
			if (!$eventInfosObject->subscriptionListNeeded)
				$dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL("events2", "editpresencerecord", "{param}");
			
			$dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("events2", "deletepresencerecord", "{param}");
		}
		catch (Exception $e)
		{
			$this->eventDateObject = null;
			$this->eventInfosObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		$conn->close();	
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['eventObj'] = $eventInfosObject;
		$this->view_PageData['eventDateObj'] = $eventDateObject;
		$this->view_PageData['presenceCount'] = $presenceCount;
	}
	
	public function pre_markpresence()
	{
		$this->title = "SisEPI - Marcar presença em lista de presença";
		$this->subtitle = "Marcar presença";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 8;
	}
	
	public function markpresence()
	{
		$eventDateId = isset($_GET['eventDateId']) && is_numeric($_GET['eventDateId']) ? $_GET['eventDateId'] : null;
		
		$conn = createConnectionAsEditor();
		
		$eventDateObject = null; $eventInfosObject = null; $subscriptionList = null;
		try
		{
			$eventDateObject = new GenericObjectFromDataRow(getEventDate($eventDateId, $conn));
			$eventInfosObject = new GenericObjectFromDataRow(getEventBasicInfos($eventDateObject->eventId, $conn));
			
			if ($eventInfosObject->subscriptionListNeeded)
				$subscriptionList = getSubscriptionList($eventDateObject->eventId, $conn);
			
		}
		catch (Exception $e)
		{
			$eventDateObject = null;
			$eventInfosObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		$conn->close();
		
		$this->view_PageData['eventObj'] = $eventInfosObject;
		$this->view_PageData['eventDateObj'] = $eventDateObject;
		$this->view_PageData['subscriptionList'] = $subscriptionList;
	}
	
	public function pre_editpresencerecord()
	{
		$this->title = "SisEPI - Editar presença";
		$this->subtitle = "Editar presença";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 7;
	}
	
	public function editpresencerecord()
	{
		$presenceId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;

		$presenceObject = null; $eventDateObject = null; $eventObject = null;
		$conn = createConnectionAsEditor();
		try
		{
			$presenceObject = new GenericObjectFromDataRow(getSinglePresenceRecordNoSubs($presenceId, $conn));
			$eventDateObject = new GenericObjectFromDataRow(getEventDate($presenceObject->eventDateId, $conn));
			$eventObject = new GenericObjectFromDataRow(getEventBasicInfos($presenceObject->eventId, $conn));			
		}
		catch (Exception $e)
		{
			$presenceObject = null;
			$eventDateObject = null;
			$eventObject = null;
			$this->pageMessages[] = "Registro não localizado.";
			$this->pageMessages[] = $e->getMessage();
		}
		$conn->close();	
		
		$this->view_PageData['presenceObj'] = $presenceObject;
		$this->view_PageData['eventDateObj'] = $eventDateObject;
		$this->view_PageData['eventObj'] = $eventObject;
	}
	
	public function pre_deletesubscription()
	{
		$this->title = "SisEPI - Excluir inscrição";
		$this->subtitle = "Excluir inscrição";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 6;
	}
	
	public function deletesubscription()
	{
		$subsId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;
		
		$subsObj = null;
		if (!isset($_GET["message"]))
		{
			try
			{
				$subsObj = new GenericObjectFromDataRow(getSingleSubscription($subsId));
			}
			catch (Exception $e)
			{
				$subsObj = null;
				$this->pageMessages[] = "Registro não localizado.";
				$this->pageMessages[] = $e->getMessage();
			}
		}
		
		$this->view_PageData['subsObj'] = $subsObj;
	}
	
	public function pre_deletepresencerecord()
	{
		$this->title = "SisEPI - Excluir presença";
		$this->subtitle = "Excluir presença";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 5;
	}
	
	public function deletepresencerecord()
	{
		$presenceId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;
		$presenceObject = null; $eventDateObject = null; $eventObject = null;
		
		if (!isset($_GET["message"]))
		{
			$conn = createConnectionAsEditor();
			try
			{
				$presenceObject = new GenericObjectFromDataRow(getSinglePresenceRecord($presenceId, $conn));
				$eventDateObject = new GenericObjectFromDataRow(getEventDate($presenceObject->eventDateId, $conn));
				$eventObject = new GenericObjectFromDataRow(getEventBasicInfos($presenceObject->eventId, $conn));
				
				if (!$eventObject->subscriptionListNeeded)
					$presenceObject = new GenericObjectFromDataRow(getSinglePresenceRecordNoSubs($this->presenceId, $conn));
				
			}
			catch (Exception $e)
			{
				$presenceObject = null;
				$eventDateObject = null;
				$eventObject = null;
				array_push($this->pageMessages, "Registro não localizado.");
				array_push($this->pageMessages, $e->getMessage());
			}
			$conn->close();		
		}
		
		$this->view_PageData['presenceObj'] = $presenceObject;
		$this->view_PageData['eventDateObj'] = $eventDateObject;
		$this->view_PageData['eventObj'] = $eventObject;
	}
	
}