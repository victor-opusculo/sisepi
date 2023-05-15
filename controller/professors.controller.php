<?php
require_once("model/Database/professors.database.php");
require_once("model/GenericObjectFromDataRow.class.php");
require_once "vendor/autoload.php";

use \SisEpi\Model\Database\Connection;
use \SisEpi\Model\Professors\Professor;

final class professors extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Docentes";
		$this->subtitle = "Docentes";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 1;
	}

	public const EXTRA_COLUMN_TOPICS_OF_INTEREST = 1;

	public static function checkForExtraColumnFlag($flagValue)
	{
		if (isset($_GET["extraColumns"]) && is_numeric($_GET["extraColumns"]))
			return (int)$_GET["extraColumns"] & $flagValue;
		else
			return false;
	}
	
	public function home()
	{
		require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");

		$conn = Connection::get();

		$paginatorComponent = null;
		$dataGridComponent = null;
		try
		{
			$getter = new Professor();
			$getter->setCryptKey(Connection::getCryptoKey());
			$paginatorComponent = new PaginatorComponent($getter->getCount($conn, $_GET["q"] ?? ""), 20);

			$profs = $getter->getMultiplePartially($conn, $paginatorComponent->pageNum, $paginatorComponent->numResultsOnPage, $_GET['orderBy'] ?? '', $_GET['q'] ?? '');

			$transformRules =
			[
				'id' => fn($p) => $p->id,
				'Nome' => fn($p) => $p->name,
				'E-mail' => fn($p) => $p->email
			];

			if ($_GET['extraColumns'] ?? 0 & self::EXTRA_COLUMN_TOPICS_OF_INTEREST)
				$transformRules['Temas de interesse'] = fn($p) => $p->topicsOfInterest;
			
			$dataGridComponent = new DataGridComponent(Data\transformDataRows($profs, $transformRules));
			$dataGridComponent->columnsToHide[] = "id";
			$dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("professors", "view", "{param}");
			$dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL("professors", "edit", "{param}");
			$dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("professors", "delete", "{param}");
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
	}
	
	public function pre_view()
	{
		$this->title = "SisEPI - Ver docente";
		$this->subtitle = "Ver docente";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 1;
	}
	
	public function view()
	{
		require_once "model/Database/terms.settings.database.php";
		require_once "controller/component/DataGrid.class.php";

		$profId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;

		$conn = Connection::get();
		$profObject = null;
		$profPersonalDocs = null;
		$consentFormTermInfos = null;
		$IrDataGrid = null;
		$otpsDataGrid = null;
		try
		{
			$getter = new Professor();
			$getter->id = $profId;
			$getter->setCryptKey(Connection::getCryptoKey());
			$profObject = $getter->getSingle($conn);
			$profObject->fetchInformesRendimentos($conn);
			$profObject->fetchOtps($conn);

			$IrDataGrid = new DataGridComponent(Data\transformDataRows($profObject->informeRendimentosAttachments, 
			[
				'id' => fn($i) => $i->id,
				'Ano-calendário' => fn($i) => $i->year
			]));
			$IrDataGrid->RudButtonsFunctionParamName = 'id';
			$IrDataGrid->columnsToHide[] = 'id';
			$IrDataGrid->deleteButtonURL = URL\URLGenerator::generateSystemURL('professors2', 'deleteinformerendimentos', '{param}');
			$IrDataGrid->detailsButtonURL = URL\URLGenerator::generateFileURL('generate/viewProfessorIrFile.php', 'id={param}');

			$otpsDataGrid = new DataGridComponent(Data\transformDataRows($profObject->otps,
			[
				'ID' => fn($o) => $o->id,
				'Expira em' => fn($o) => date_create($o->expiryDateTime)->format('d/m/Y H:i:s')
			]));
			$otpsDataGrid->RudButtonsFunctionParamName = 'ID';
			$otpsDataGrid->deleteButtonURL = URL\URLGenerator::generateSystemURL('professors3', 'deleteotp', '{param}');
			$otpsDataGrid->editButtonURL = URL\URLGenerator::generateSystemURL('professors3', 'editotp', '{param}');

			$docsDrs = getProfessorPersonalDocs($profId, $conn);
			$profPersonalDocs = isset($docsDrs) ? array_map( fn($dr) => new GenericObjectFromDataRow($dr), $docsDrs) : null;

			$consentFormTermInfos = getSingleTerm($profObject->consentForm, $conn);
		}
		catch (Exception $e)
		{
			$profObject = null;
			$profPersonalDocs = null;
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }
		
		$this->view_PageData['profObject'] = $profObject;
		$this->view_PageData['IrDgComp'] = $IrDataGrid;
		$this->view_PageData['otpsDgComp'] = $otpsDataGrid;
		$this->view_PageData['profPersonalDocs'] = $profPersonalDocs;
		$this->view_PageData['consentFormTermInfos'] = $consentFormTermInfos;
	}
		
	public function pre_edit()
	{
		$this->title = "SisEPI - Editar docente";
		$this->subtitle = "Editar docente";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 2;
	}
	
	public function edit()
	{
		$profId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;
		$profObject = null;	
		$conn = createConnectionAsEditor();
		$races = null;
		try
		{
			$getter = new \SisEpi\Model\Professors\Professor();
			$getter->id = $profId;
			$getter->setCryptKey(getCryptoKey());
			$profObject = $getter->getSingle($conn);

			$racesGetter = new \SisEpi\Model\Enums\Enum();
			$racesGetter->type = 'RACE';
			$races = $racesGetter->getAllFromType($conn);
		}
		catch (Exception $e)
		{
			$profObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }
		
		$this->view_PageData['profObject'] = $profObject;
		$this->view_PageData['races'] = $races;
	}
	
	public function pre_delete()
	{
		$this->title = "SisEPI - Excluir docente";
		$this->subtitle = "Excluir docente";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 3;
	}
	
	public function delete()
	{
		$profId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : null;
		$profObject = null;
		if (!isset($_GET["messages"])) $profObject = new GenericObjectFromDataRow(getSingleProfessor($profId));
		
		if ($profObject === null && !isset($_GET["messages"]))
			$this->pageMessages[] = "Registro não localizado.";
		
		$this->view_PageData['profObject'] = $profObject;
	}

	public function pre_uploadpersonaldocs()
	{
		$this->title = "SisEPI - Editar uploads de docente";
		$this->subtitle = "Editar uploads de docente";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 2;
	}

	public function uploadpersonaldocs()
	{
        require_once("model/Database/generalsettings.database.php");
        require_once("model/GenericObjectFromDataRow.class.php");

		$professorId = isset($_GET['professorId']) && isId($_GET['professorId']) ? $_GET['professorId'] : null;

        $conn = createConnectionAsEditor();
        $professorDocsAttachments = null;
        $professorDocTypes = null;
		$professorObj = null;
        try
        {
			$professorObj = new GenericObjectFromDataRow(getSingleProfessor($professorId, $conn));
			$profPersonalDocsDRs = getProfessorPersonalDocs($professorId, $conn) ?? [];
            $professorDocsAttachments = array_map( fn($dr) => new GenericObjectFromDataRow($dr), $profPersonalDocsDRs);
            $professorDocTypes = json_decode(readSetting('PROFESSORS_DOCUMENT_TYPES', $conn), true);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['professorDocsAttachments'] = $professorDocsAttachments;
        $this->view_PageData['docTypes'] = $professorDocTypes;
        $this->view_PageData['professorObj'] = $professorObj;
	}
}