<?php
require_once("model/database/librarycollection.database.php");

final class librarycollection extends BaseController
{
	protected function pre_home()
	{
		$this->title = "SisEPI - Acervo da biblioteca";
		$this->subtitle = "Acervo da biblioteca";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 2;
	}
	
	protected function home()
	{
		require_once("component/DataGrid.class.php");
		require_once("component/Paginator.class.php");
		
		$conn = createConnectionAsEditor();
		
		$paginatorComponent = new PaginatorComponent(getCollectionCount(($_GET["colTypeId"] ?? ""), ($_GET["q"] ?? ""), $conn), 20);
		
		$createFinalDataRowsTable = function($conn) use ($paginatorComponent)
		{
			$collection = getCollectionPartially($paginatorComponent->pageNum, 
													$paginatorComponent->numResultsOnPage,
													($_GET["orderBy"] ?? ""),
													($_GET["colTypeId"] ?? ""),
													($_GET["q"] ?? ""), $conn);
			$output = [];
			
			if ($collection)
				foreach ($collection as $pub)
				{
					$row = [];
					$row["ID"] = $pub["id"];
					$row["Cat. Acervo"] = $pub["collectionTypeName"];
					$row["Título"] = $pub["title"];
					$row["Autor"] = $pub["author"];
					$row["CDU/CDD/ISBN"] = formatCDU_CDD_ISBN($pub);
					if (checkForExtraColumnFlag(1)) $row["Edição"] = $pub["edition"];
					if (checkForExtraColumnFlag(2)) $row["Volume"] = $pub["volume"];
					if (checkForExtraColumnFlag(4)) $row["Exemplar"] = $pub["copyNumber"];
					
					$output[] = $row;
				}
				
			return $output;
		};
		
		function formatCDU_CDD_ISBN($pubDataRow)
		{
			$output = "";
			if ($pubDataRow["cdu"])
			{
				$output .= "CDU: " . $pubDataRow["cdu"];
				$output .= $pubDataRow["cdd"] || $pubDataRow["isbn"] ? PHP_EOL : "";
			}
			
			if ($pubDataRow["cdd"])
			{
				$output .= "CDD: " . $pubDataRow["cdd"];
				$output .= $pubDataRow["isbn"] ? PHP_EOL : "";
			}
			
			if ($pubDataRow["isbn"])
			{
				$output .= "ISBN: " . $pubDataRow["isbn"];
			}
			
			return $output;
		}
		
		function checkForExtraColumnFlag($flagValue)
		{
			if (isset($_GET["extraColumns"]) && is_numeric($_GET["extraColumns"]))
				return (int)$_GET["extraColumns"] & $flagValue;
			else
				return false;
		}
		
		$this->view_PageData['collectionTypesList'] = getCollectionTypes($conn);
		
		$dataGridComponent = new DataGridComponent($createFinalDataRowsTable($conn));
		$dataGridComponent->RudButtonsFunctionParamName = "ID";
		$dataGridComponent->columnNameAsDetailsButton = "Título";
		$dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("librarycollection", "view", "{param}");
		
		$conn->close();
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
	}
	
	protected function pre_view()
	{
		$this->title = "SisEPI - Ver publicação";
		$this->subtitle = "Ver publicação";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 2;
	}
	
	protected function view()
	{
		require_once("controller/component/DataGrid.class.php");
		require_once("model/GenericObjectFromDataRow.class.php");
		
		$conn = createConnectionAsEditor();
		
		$publicationId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		$publicationObject; $loansDataGridComponent; $reservationsDataGridComponent;
		
		$createFinalLoanDataRowsTable = function($conn) use ($publicationId)
		{
			$dataRows = getLoanListLimited($publicationId, $conn);
			
			$checkIcon = new DataGridIcon("pics/check.png", "Sim");
			$checkLateIcon = new DataGridIcon("pics/check.png", "Sim"); $checkLateIcon->textAfterIcon = "Atrasado";
			
			$output = [];
			if ($dataRows)
				foreach ($dataRows as $dr)
				{
					$row = [];
					$row["id"] = $dr["id"];
					$row["publicationId"] = $dr["publicationId"];
					$row["Usuário"] = $dr["userName"];
					$row["Data de empréstimo"] = date_format(date_create($dr["borrowDatetime"]), "d/m/Y H:i:s");
					$row["Devolução esperada"] = date_format(date_create($dr["expectedReturnDatetime"]), "d/m/Y H:i:s");
					$row["Finalizado?"] = (bool)$dr["isReturned"] ? ( $dr["returnedLate"] ? $checkLateIcon : $checkIcon) : '';
					
					$output[] = $row;
				}
			return $output;
		};

		$createFinalReservationsDataRowsTable = function($conn) use ($publicationId)
		{
			$dataRows = getReservationsListLimited($publicationId, $conn);
			$output = [];
			
			$checkIcon = new DataGridIcon("pics/check.png", "Sim");
			$wrongIcon = new DataGridIcon("pics/wrong.png", "Invalidada");
			
			if ($dataRows)
				foreach ($dataRows as $dr)
				{
					$row = [];
					$row["id"] = $dr["id"];
					$row["publicationId"] = $dr["publicationId"];
					$row["Usuário"] = $dr["userName"];
					$row["Data de reserva"] = date_format(date_create($dr["reservationDatetime"]), "d/m/Y H:i:s");
					$row["Atendida?"] = (bool)$dr["isFinalized"] ? $checkIcon : ($dr["invalidatedDatetime"] ? $wrongIcon : 'Aguardando');
					
					$output[] = $row;
				}
			return $output;
		};
		
		try
		{
			$publicationObject = new GenericObjectFromDataRow(getSinglePublication($publicationId, $conn));
			
			$loansDataGridComponent = new DataGridComponent($createFinalLoanDataRowsTable($conn));
			$loansDataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("libraryborrowedpubs", "view", "{param}");
			$loansDataGridComponent->columnsToHide[] = "id";
			$loansDataGridComponent->columnsToHide[] = "publicationId";
			
			$reservationsDataGridComponent = new DataGridComponent($createFinalReservationsDataRowsTable($conn));
			$reservationsDataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("libraryreservations", "view", "{param}");
			$reservationsDataGridComponent->columnsToHide[] = "id";
			$reservationsDataGridComponent->columnsToHide[] = "publicationId";
		}
		catch (Exception $e)
		{
			$publicationObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['pubObj'] = $publicationObject;
		$this->view_PageData['loansDgComp'] = $loansDataGridComponent;
		$this->view_PageData['reservationsDgComp'] = $reservationsDataGridComponent;
	}
	
	protected function pre_create()
	{
		$this->title = "SisEPI - Cadastrar publicação";
		$this->subtitle = "Cadastrar publicação";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 9;
	}
	
	protected function create()
	{
		$conn = createConnectionAsEditor();
		
		$periodicityDataRows = null;
		$collTypesDataRows = null;
		$acqTypesDataRows = null;
		try
		{
			$periodicityDataRows = getPeriodicityTypes($conn);
			$collTypesDataRows = getCollectionTypes($conn);
			$acqTypesDataRows = getAcquisitionTypes($conn);
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['periodicityTypes']  = $periodicityDataRows;
		$this->view_PageData['collTypes'] = $collTypesDataRows;
		$this->view_PageData['acqTypes'] = $acqTypesDataRows;
	}
	
	protected function pre_edit()
	{
		$this->title = "SisEPI - Editar publicação";
		$this->subtitle = "Editar publicação";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 3;
	}
	
	protected function edit()
	{
		require_once("model/GenericObjectFromDataRow.class.php");
		
		$conn = createConnectionAsEditor();
		
		$publicationId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		$publicationObject; $periodicityDataRows; $collTypesDataRows; $acqTypesDataRows;
		
		try
		{
			$publicationObject = new GenericObjectFromDataRow(getSinglePublication($publicationId, $conn));
			$periodicityDataRows = getPeriodicityTypes($conn);
			$collTypesDataRows = getCollectionTypes($conn);
			$acqTypesDataRows = getAcquisitionTypes($conn);
		}
		catch (Exception $e)
		{
			$publicationObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['pubObj'] = $publicationObject;
		
		$this->view_PageData['periodicityTypes']  = $periodicityDataRows;
		$this->view_PageData['collTypes'] = $collTypesDataRows;
		$this->view_PageData['acqTypes'] = $acqTypesDataRows;
	}
	
	protected function pre_delete()
	{
		$this->title = "SisEPI - Excluir publicação";
		$this->subtitle = "Excluir publicação";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 4;
	}
	
	protected function delete()
	{
		require_once("model/GenericObjectFromDataRow.class.php");
		$conn = createConnectionAsEditor();
		
		$publicationId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		$publicationObject;
		try
		{
			$publicationObject = new GenericObjectFromDataRow(getSinglePublication($publicationId, $conn));
		}
		catch (Exception $e)
		{
			$publicationObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['pubObj'] = $publicationObject;
	}
}