<?php
require_once("model/Database/libraryborrowedpubs.database.php");

final class libraryborrowedpubs extends BaseController
{
	protected function pre_home()
	{
		$this->title = "SisEPI - Biblioteca: Empréstimos";
		$this->subtitle = "Empréstimos";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 10;
	}
	
	protected function home()
	{
		require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");
		
		$conn = createConnectionAsEditor();
		
		$paginatorComponent = new PaginatorComponent(getBorrowedPubsCount(($_GET["q"] ?? ""), $conn), 20);
		
		$createFinalDataRowsTable = function ($conn) use ($paginatorComponent)
		{
			$bpubs = getBorrowedPubsPartially($paginatorComponent->pageNum, 
													$paginatorComponent->numResultsOnPage,
													($_GET["orderBy"] ?? ""),
													($_GET["q"] ?? ""), $conn);
			$output = [];
			
			$checkIcon = new DataGridIcon("pics/check.png", "Sim");
			$checkLateIcon = new DataGridIcon("pics/check.png", "Sim"); $checkLateIcon->textAfterIcon = "Atrasado";
			
			if ($bpubs)
				foreach ($bpubs as $bp)
				{
					$row = [];
					$row["ID"] = $bp["id"];
					$row["Publicação"] = $bp["title"];
					$row["Usuário"] = $bp["userName"];
					$row["Data do empréstimo"] = date_format(date_create($bp["borrowDatetime"]), "d/m/Y H:i:s");
					$row["Retorno previsto"] = date_format(date_create($bp["expectedReturnDatetime"]), "d/m/Y H:i:s");
					$row["Finalizado?"] = $bp["isReturned"] ? ( $bp["returnedLate"] ? $checkLateIcon : $checkIcon) : '';
					
					$output[] = $row;
				}
				
			return $output;
		};
		
		$dataGridComponent = new DataGridComponent($createFinalDataRowsTable($conn));
		$dataGridComponent->RudButtonsFunctionParamName = "ID";
		$dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("libraryborrowedpubs", "view", "{param}");
		
		$conn->close();
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
	}
	
	protected function pre_view()
	{
		$this->title = "SisEPI - Ver empréstimo";
		$this->subtitle = "Ver empréstimo";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 10;
	}
	
	protected function view()
	{
		require_once("model/GenericObjectFromDataRow.class.php");
		
		$conn = createConnectionAsEditor();
		
		$borrowedPubId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		$borrowedPubObject = null;
		try
		{
			$borrowedPubObject = new GenericObjectFromDataRow(getSingleBorrowedPub($borrowedPubId, $conn));
		}
		catch (Exception $e)
		{
			$borrowedPubObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['bpubObj'] = $borrowedPubObject;
	}
	
	protected function pre_create()
	{
		$this->title = "SisEPI - Emprestar publicação";
		$this->subtitle = "Emprestar";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 13;
	}
	
	protected function create()
	{
	}
	
	protected function pre_finalize()
	{
		$this->title = "SisEPI - Biblioteca: Finalizar empréstimo";
		$this->subtitle = "Finalizar empréstimo";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 11;
	}
	
	protected function finalize()
	{
		require_once("model/GenericObjectFromDataRow.class.php");
		
		$conn = createConnectionAsEditor();
		
		$bpubId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		$bpubObject = null;
		try
		{
			$bpubObject = new GenericObjectFromDataRow(getSingleBorrowedPub($bpubId, $conn));
			if ($bpubObject->returnDatetime)
				throw new Exception("Este empréstimo já foi finalizado.");
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['bpubObj'] = $bpubObject;
	}
	
	
}