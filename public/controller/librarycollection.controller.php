<?php
require_once("model/database/librarycollection.database.php");

final class librarycollection extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Biblioteca";
		$this->subtitle = "Biblioteca";
	}
	
	public function home()
	{
		require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");
		
		$conn = createConnectionAsEditor();
		
		$paginatorComponent = new PaginatorComponent(getCollectionCount(($_GET["q"] ?? ""), $conn), 20);
		
		$createFinalDataRowsTable = function($conn) use ($paginatorComponent)
		{
			$collection = getCollectionPartially($paginatorComponent->pageNum, 
													$paginatorComponent->numResultsOnPage,
													($_GET["orderBy"] ?? ""),
													($_GET["q"] ?? ""), $conn);
			$output = [];
			if ($collection)
				foreach ($collection as $pub)
				{
					$row = [];
					$row["Código"] = $pub["id"];
					$row["Cat. Acervo"] = $pub["collectionTypeName"];
					$row["Título"] = $pub["title"];
					$row["Autor"] = $pub["author"];
					
					$output[] = $row;
				}
				
			return $output;
		};
		
		$dataGridComponent = new DataGridComponent($createFinalDataRowsTable($conn));
		$dataGridComponent->RudButtonsFunctionParamName = "Código";
		$dataGridComponent->columnNameAsDetailsButton = "Título";
		$dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("librarycollection", "view", "{param}");
				
		$conn->close();
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
	}
	
	public function pre_view()
	{
		$this->title = "SisEPI - Ver publicação";
		$this->subtitle = "Ver publicação";
	}
	
	public function view()
	{
		require_once("model/GenericObjectFromDataRow.class.php");
		
		$conn = createConnectionAsEditor();
		
		$publicationId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		$publicationObject;
		$numOfValidReservations;
		try
		{
			$publicationObject = new GenericObjectFromDataRow(getSinglePublication($publicationId, $conn));
			$numOfValidReservations = getValidReservationsCount($publicationId, $conn);
		}
		catch (Exception $e)
		{
			$publicationObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['pubObj'] = $publicationObject;
		$this->view_PageData['resNumber'] = $numOfValidReservations;
	}
}