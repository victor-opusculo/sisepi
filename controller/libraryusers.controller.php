<?php
require_once("model/database/libraryusers.database.php");

final class libraryusers extends BaseController
{
	protected function pre_home()
	{
		$this->title = "SisEPI - Usuários da biblioteca";
		$this->subtitle = "Usuários da biblioteca";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 5;
	}
	
	protected function home()
	{
		require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");

		$conn = createConnectionAsEditor();
		
		$paginatorComponent = new PaginatorComponent(getUsersCount(($_GET["q"] ?? ""), $conn), 20);
		
		$createFinalDataRowsTable = function($conn) use ($paginatorComponent)
		{
			$users = getUsersPartially($paginatorComponent->pageNum, 
													$paginatorComponent->numResultsOnPage,
													($_GET["orderBy"] ?? ""),
													($_GET["q"] ?? ""), $conn);
			$output = [];
			
			if ($users)
				foreach ($users as $u)
				{
					$row = [];
					$row["ID"] = $u["id"];
					$row["Nome"] = $u["name"];
					$row["E-mail"] = $u["email"];
					$row["Tipo"] = $u["typeName"];
					
					$output[] = $row;
				}
				
			return $output;
		};
		
		$dataGridComponent = new DataGridComponent($createFinalDataRowsTable($conn));
		$dataGridComponent->RudButtonsFunctionParamName = "ID";
		$dataGridComponent->columnNameAsDetailsButton = "Nome";
		$dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("libraryusers", "view", "{param}");
		
		$conn->close();
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
	}
	
	protected function pre_view()
	{
		$this->title = "SisEPI - Ver usuário da biblioteca";
		$this->subtitle = "Ver usuário da biblioteca";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 5;
	}
	
	protected function view()
	{
		require_once("controller/component/DataGrid.class.php");
		require_once("model/GenericObjectFromDataRow.class.php");
		$conn = createConnectionAsEditor();
		
		$userId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		
		$createFinalLoanDataRowsTable = function($conn) use ($userId)
		{
			$dataRows = getLoanListLimited($userId, $conn);
			
			$output = [];
			
			$checkIcon = new DataGridIcon("pics/check.png", "Sim");
			$checkLateIcon = new DataGridIcon("pics/check.png", "Sim"); $checkLateIcon->textAfterIcon = "Atrasado";
			
			if ($dataRows)
				foreach ($dataRows as $dr)
				{
					$row = [];
					$row["id"] = $dr["id"];
					$row["publicationId"] = $dr["publicationId"];
					$row["Publicação"] = $dr["title"];
					$row["Data de empréstimo"] = date_format(date_create($dr["borrowDatetime"]), "d/m/Y H:i:s");
					$row["Devolução esperada"] = date_format(date_create($dr["expectedReturnDatetime"]), "d/m/Y H:i:s");
					$row["Finalizado?"] = (bool)$dr["isReturned"] ? ( $dr["returnedLate"] ? $checkLateIcon : $checkIcon) : '';
					
					$output[] = $row;
				}
			return $output;
		};

		$createFinalReservationsDataRowsTable = function($conn) use ($userId)
		{
			$dataRows = getReservationsListLimited($userId, $conn);
			$output = [];
			
			$checkIcon = new DataGridIcon("pics/check.png", "Sim");
			$wrongIcon = new DataGridIcon("pics/wrong.png", "Invalidada");
			
			if ($dataRows)
				foreach ($dataRows as $dr)
				{
					$row = [];
					$row["id"] = $dr["id"];
					$row["publicationId"] = $dr["publicationId"];
					$row["Publicação"] = $dr["title"];
					$row["Data de reserva"] = date_format(date_create($dr["reservationDatetime"]), "d/m/Y H:i:s");
					$row["Atendida?"] = (bool)$dr["isFinalized"] ? $checkIcon : ($dr["invalidatedDatetime"] ? $wrongIcon : 'Aguardando');
					
					$output[] = $row;
				}
			return $output;
		};
		
		$loansDataGridComponent = null; 
		$reservationsDataGridComponent = null;
		
		try
		{
			$userObject = new GenericObjectFromDataRow(getSingleUser($userId, $conn));
			$userObject->lateDevolutionsCount = getLateDevolutionsCount($userId, $conn);
			
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
			$userObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['userObj'] = $userObject;
		$this->view_PageData['loansDgComp'] = $loansDataGridComponent;
		$this->view_PageData['reservationsDgComp'] = $reservationsDataGridComponent;
	}
	
	protected function pre_create()
	{
		$this->title = "SisEPI - Criar usuário da biblioteca";
		$this->subtitle = "Criar usuário da biblioteca";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 8;
	}
	
	protected function create()
	{
		require_once("model/database/generalsettings.database.php");
		require_once("model/GenericObjectFromDataRow.class.php");

		$conn = createConnectionAsEditor();
		
		try
		{
			$userTypesDataRows = getUserTypes($conn);
			$consentFormFile = readSetting("LIBRARY_USERS_CONSENT_FORM", $conn);
			$consentFormVersion = readSetting("LIBRARY_USERS_CONSENT_FORM_VERSION", $conn);
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['consentFormFile'] = $consentFormFile;
		$this->view_PageData['consentFormVersion'] = $consentFormVersion;
		$this->view_PageData['userTypes'] = $userTypesDataRows;
	}
	
	protected function pre_edit()
	{
		$this->title = "SisEPI - Editar usuário da biblioteca";
		$this->subtitle = "Editar usuário da biblioteca";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 6;
	}
	
	protected function edit()
	{
		require_once("model/database/generalsettings.database.php");
		require_once("model/GenericObjectFromDataRow.class.php");
		
		$conn = createConnectionAsEditor();
		
		$userId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		$userObject = null;
		$userTypesDataRows = null;
		$currentConsentFormFile = null;
		
		try
		{
			$userObject = new GenericObjectFromDataRow(getSingleUser($userId, $conn));
			$userTypesDataRows = getUserTypes($conn);
			$currentConsentFormFile = readSetting("LIBRARY_USERS_CONSENT_FORM", $conn);
			$consentFormVersion = readSetting("LIBRARY_USERS_CONSENT_FORM_VERSION", $conn);
		}
		catch (Exception $e)
		{
			$userObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['currentConsentFormFile'] = $currentConsentFormFile;
		$this->view_PageData['consentFormVersion'] = $consentFormVersion;
		$this->view_PageData['userObj'] = $userObject;
		$this->view_PageData['userTypes'] = $userTypesDataRows;
	}
	
	protected function pre_delete()
	{
		$this->title = "SisEPI - Excluir usuário da biblioteca";
		$this->subtitle = "Excluir usuário da biblioteca";
		
		$this->moduleName = "LIBR";
		$this->permissionIdRequired = 7;
	}
	
	protected function delete()
	{
		require_once("model/GenericObjectFromDataRow.class.php");
		
		$conn = createConnectionAsEditor();
		
		$userId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		$userObject = null;
		try
		{
			$userObject = new GenericObjectFromDataRow(getSingleUser($userId, $conn));
		}
		catch (Exception $e)
		{
			$userObject = null;
			$pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
		
		$this->view_PageData['userObj'] = $userObject;
	}
}