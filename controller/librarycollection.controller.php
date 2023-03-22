<?php
require_once("model/database/librarycollection.database.php");
require_once "model/librarycollection/Publication.php";

use \SisEpi\Model\LibraryCollection\Publication;

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

		$pubGetter = new Publication();
		$conn = createConnectionAsEditor();
		
		$paginatorComponent = new PaginatorComponent($pubGetter->getCount($conn, $_GET['q'] ?? ''), 20);
		
		$createFinalDataRowsTable = function($conn) use ($paginatorComponent, $pubGetter)
		{
			$collection = $pubGetter->getMultiplePartially($conn, 
															$paginatorComponent->pageNum,
															$paginatorComponent->numResultsOnPage,
															($_GET["orderBy"] ?? ""),
															($_GET["q"] ?? ""));
			$output = [];
			if ($collection)
				foreach ($collection as $pub)
				{
					$row = [];
					$row["ID"] = $pub->id;
					$row["Título"] = $pub->title;
					$row["Autor"] = $pub->author;
					$row["CDU/CDD/ISBN"] = formatCDU_CDD_ISBN($pub);
					if (checkForExtraColumnFlag(1)) $row["Edição"] = $pub->edition;
					if (checkForExtraColumnFlag(2)) $row["Volume"] = $pub->volume;
					if (checkForExtraColumnFlag(4)) $row["Exemplar"] = $pub->copyNumber;
					
					$output[] = $row;
				}
				
			return $output;
		};
		
		function formatCDU_CDD_ISBN($pubEntity)
		{
			$output = "";
			if ($pubEntity->cdu)
			{
				$output .= "CDU: " . $pubEntity->cdu;
				$output .= $pubEntity->cdd || $pubEntity->isbn ? PHP_EOL : "";
			}
			
			if ($pubEntity->cdd)
			{
				$output .= "CDD: " . $pubEntity->cdd;
				$output .= $pubEntity->isbn ? PHP_EOL : "";
			}
			
			if ($pubEntity->isbn)
			{
				$output .= "ISBN: " . $pubEntity->isbn;
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
		
		$conn = createConnectionAsEditor();
		
		$publicationId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;

		$getter = new Publication();
		$getter->id = $publicationId;

		$publicationObject = null;
		$loansDataGridComponent = null;
		$reservationsDataGridComponent = null;

		try
		{
			$publicationObject = $getter->getSingle($conn);
			$publicationObject->setCryptKey(getCryptoKey());
		
			$createFinalLoanDataRowsTable = function($conn) use ($publicationObject)
			{
				$dataRows = $publicationObject->getLoanListLimited($conn);
				
				$checkIcon = new DataGridIcon("pics/check.png", "Sim");
				$checkLateIcon = new DataGridIcon("pics/check.png", "Sim"); $checkLateIcon->textAfterIcon = "Atrasado";
				
				return Data\transformDataRows($dataRows, 
				[
					'id' => fn($dr) => $dr['id'],
					'publicationId' => fn($dr) => $dr['publicationId'],
					'Usuário' => fn($dr) => $dr['userName'],
					'Data de empréstimo' => fn($dr) => date_format(date_create($dr["borrowDatetime"]), "d/m/Y H:i:s"),
					'Devolução esperada' => fn($dr) => date_format(date_create($dr["expectedReturnDatetime"]), "d/m/Y H:i:s"),
					'Finalizado?' => fn($dr) => (bool)$dr["isReturned"] ? ( $dr["returnedLate"] ? $checkLateIcon : $checkIcon) : ''
				]);
			};

			$createFinalReservationsDataRowsTable = function($conn) use ($publicationObject)
			{
				$dataRows = $publicationObject->getReservationsListLimited($conn);
				
				$checkIcon = new DataGridIcon("pics/check.png", "Sim");
				$wrongIcon = new DataGridIcon("pics/wrong.png", "Invalidada");
				
				return Data\transformDataRows($dataRows,
				[
					"id" => fn($dr) => $dr["id"],
					"publicationId" => fn($dr) => $dr["publicationId"],
					"Usuário" => fn($dr) => $dr["userName"],
					"Data de reserva" => fn($dr) => date_format(date_create($dr["reservationDatetime"]), "d/m/Y H:i:s"),
					"Atendida?" => fn($dr) => (bool)$dr["isFinalized"] ? $checkIcon : ($dr["invalidatedDatetime"] ? $wrongIcon : 'Aguardando')
				]);
			};
			
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
		require_once 'model/librarycollection/AcquisitionType.php';

		$conn = createConnectionAsEditor();
		$acqTypesDataRows = null;
		try
		{
			$acqTypesDataRows = (new \SisEpi\Model\LibraryCollection\AcquisitionType())->getAll($conn);
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
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
		require_once 'model/librarycollection/AcquisitionType.php';
		
		$conn = createConnectionAsEditor();
		
		$publicationId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		$publicationObject = null;
		$acqTypesObjects = null;
		
		try
		{
			$pubGetter = new Publication();
			$pubGetter->id = $publicationId;
			$publicationObject = $pubGetter->getSingle($conn);
			$acqTypesObjects = (new \SisEpi\Model\LibraryCollection\AcquisitionType)->getAll($conn);
		}
		catch (Exception $e)
		{
			$publicationObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['pubObj'] = $publicationObject;
		$this->view_PageData['acqTypes'] = $acqTypesObjects;
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
		$conn = createConnectionAsEditor();
		
		$publicationId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		$publicationObject = null;
		$getter = new Publication();
		$getter->id = $publicationId;
		try
		{
			$publicationObject = $getter->getSingle($conn);
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