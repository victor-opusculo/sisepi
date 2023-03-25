<?php
require_once("model/Database/libraryreservations.database.php");

final class libraryreservations extends BaseController
{
	protected function pre_home()
	{
		$this->title = "SisEPI - Biblioteca: Reservas";
		$this->subtitle = "Reservas";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 12;
	}
	
	protected function home()
	{
		require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");
		
		$conn = createConnectionAsEditor();
		
		$paginatorComponent = new PaginatorComponent(getReservationsCount(($_GET["q"] ?? ""), $conn), 20);

		$createFinalDataRowsTable = function($conn) use ($paginatorComponent)
		{
			$res = getReservationsPartially($paginatorComponent->pageNum, 
													$paginatorComponent->numResultsOnPage,
													($_GET["orderBy"] ?? ""),
													($_GET["q"] ?? ""), $conn);
			$output = [];
			
			$checkIcon = new DataGridIcon("pics/check.png", "Sim");
			$wrongIcon = new DataGridIcon("pics/wrong.png", "Invalidada");
			
			if ($res)
				foreach ($res as $r)
				{
					$row = [];
					$row["ID"] = $r["id"];
					$row["Publicação"] = $r["title"];
					$row["Usuário"] = $r["userName"];
					$row["Data da reserva"] = date_format(date_create($r["reservationDatetime"]), "d/m/Y H:i:s");
					$row["Atendida?"] = $r["isFinalized"] == 1 ? $checkIcon : ($r["isFinalized"] == -1 ? $wrongIcon : "Aguardando");
									
					$output[] = $row;
				}
				
			return $output;
		};

		$dataGridComponent = new DataGridComponent($createFinalDataRowsTable($conn));
		$dataGridComponent->RudButtonsFunctionParamName = "ID";
		$dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("libraryreservations", "view", "{param}");
		
		$conn->close();
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
	}
	
	protected function pre_view()
	{
		$this->title = "SisEPI - Ver reserva";
		$this->subtitle = "Ver reserva";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 12;
	}
	
	protected function view()
	{
		require_once("model/GenericObjectFromDataRow.class.php");
		
		$conn = createConnectionAsEditor();
		
		$reservationId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		$reservationObject = null;
		try
		{
			$reservationObject = new GenericObjectFromDataRow(getSingleReservation($reservationId, $conn));
		}
		catch (Exception $e)
		{
			$reservationObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['resObj'] = $reservationObject;
	}
	
	protected function pre_create()
	{
		$this->title = "SisEPI - Reservar publicação";
		$this->subtitle = "Reservar";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 15;
	}
	
	protected function create()
	{
	}
	
	protected function pre_delete()
	{
		$this->title = "SisEPI - Excluir reserva";
		$this->subtitle = "Excluir reserva";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 14;
	}
	
	protected function delete()
	{
		require_once("model/GenericObjectFromDataRow.class.php");
		$conn = createConnectionAsEditor();
		
		$reservationId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		$reservationObject = null;
		try
		{
			$reservationObject = new GenericObjectFromDataRow(getSingleReservation($reservationId, $conn));
		}
		catch (Exception $e)
		{
			$reservationObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['resObj'] = $reservationObject;
	}
	
}