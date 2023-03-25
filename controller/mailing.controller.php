<?php
require_once("model/Database/mailing.database.php");

final class mailing extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Mailing";
		$this->subtitle = "Mailing";
		
		$this->moduleName = "MAIL";
		$this->permissionIdRequired = 1;
	}
	
	public function home()
	{
		require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");

		$conn = createConnectionAsEditor();
		
		$eventId = isset($_GET['eventId']) && is_numeric($_GET['eventId']) ? $_GET['eventId'] : null;
		$eventList = getEventsList($conn);
		
		$paginatorComponent = new PaginatorComponent(getMailingCount($eventId, ($_GET["q"] ?? ""), $conn), 50);
		
		$createFinalDataRowsTable = function($conn) use ($paginatorComponent, $eventId)
		{
			$events = getMailingPartially($paginatorComponent->pageNum, $paginatorComponent->numResultsOnPage, ($_GET["orderBy"] ?? ""), $eventId, ($_GET["q"] ?? ""),  $conn);
			$output = [];
			
			if($events)
			foreach ($events as $e)
			{
				$row = [];
				$row["id"] = $e["id"];
				$row["E-mail"] = $e["demail"];
				$row["Nome"] = $e["dname"];
				$row["Inscrito no evento:"] = $e["eventName"];

				array_push($output, $row);

			}
			return $output;
		};
		
		
		$dataGridComponent = new DataGridComponent($createFinalDataRowsTable($conn));
		$dataGridComponent->columnsToHide[] = "id";
		$dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("mailing", "delete", "{param}");
		
		$conn->close();
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
		$this->view_PageData['eventList'] = $eventList;
		$this->view_PageData['eventId'] = $eventId;
	}
	
	public function pre_delete()
	{
		$this->title = "SisEPI - Excluir inscrição do mailing";
		$this->subtitle = "Excluir inscrição do mailing";
		
		$this->moduleName = "MAIL";
		$this->permissionIdRequired = 2;
	}
	
	public function delete()
	{
		require_once("model/GenericObjectFromDataRow.class.php");
		
		$mailId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;
		if (!isset($_GET["messages"]))
		{
			$conn = createConnectionAsEditor();
			$mailObject = null;
			try
			{
				$mailObject = new GenericObjectFromDataRow(getSingleEmail($mailId, $conn));
			}
			catch (Exception $e)
			{
				$mailObject = null;
				array_push($this->pageMessages, "Registro não localizado.");
				array_push($this->pageMessages, $e->getMessage());
			}
			finally
			{
				$conn->close();	
			}

			$this->view_PageData['mailObject'] = $mailObject;
		}
	}
}