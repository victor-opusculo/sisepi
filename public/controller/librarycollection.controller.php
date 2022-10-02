<?php

use Model\LibraryCollection\Publication;

require_once("model/database/librarycollection.database.php");
require_once "model/librarycollection/Publication.php";

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
		
		$paginatorComponent = null;
		$dataGridComponent = null;

		try
		{
			$pubGetter = new Publication();
			
			$paginatorComponent = new PaginatorComponent($pubGetter->getCount($conn, $_GET['q'] ?? ''), 20);
		
			$createFinalDataRowsTable = function($conn) use ($paginatorComponent, $pubGetter)
			{
				$collection = $pubGetter->getMultiplePartially($conn, $paginatorComponent->pageNum, 
														$paginatorComponent->numResultsOnPage,
														($_GET["orderBy"] ?? ""),
														($_GET["q"] ?? ""));
				
				return Data\transformDataRows($collection, 
				[
					'Código' => fn($dr) => $dr->id,
					'Título' => fn($dr) => $dr->title,
					'Autor' => fn($dr) => $dr->author
				]);
			};
			
			$dataGridComponent = new DataGridComponent($createFinalDataRowsTable($conn));
			$dataGridComponent->RudButtonsFunctionParamName = "Código";
			$dataGridComponent->columnNameAsDetailsButton = "Título";
			$dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("librarycollection", "view", "{param}");
		}
		catch (\Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		finally
		{	
			$conn->close();
		}
		
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
		$conn = createConnectionAsEditor();
		
		$publicationId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		$publicationObject = null;
		$numOfValidReservations = null;
		$isAvailableForBorrowing = false;
		try
		{
			$pubGetter = new Publication();
			$pubGetter->id = $publicationId;
			$publicationObject = $pubGetter->getSingle($conn);
			$numOfValidReservations = $pubGetter->getReservationsNumber($conn);
			$isAvailableForBorrowing = $pubGetter->isAvailableForBorrowing($conn);
		}
		catch (Exception $e)
		{
			$publicationObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['pubObj'] = $publicationObject;
		$this->view_PageData['resNumber'] = $numOfValidReservations;
		$this->view_PageData['isAvailable'] = $isAvailableForBorrowing;
	}
}