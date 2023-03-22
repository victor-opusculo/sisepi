<?php
require_once("model/database/professors.database.php");
require_once("model/GenericObjectFromDataRow.class.php");
require_once "model/professors/Professor.php";

final class professors extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Docentes";
		$this->subtitle = "Docentes";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 1;
	}
	
	public function home()
	{
		require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");

		$conn = createConnectionAsEditor();
	
		$paginatorComponent = new PaginatorComponent(getProfessorsCount(($_GET["q"] ?? ""), $conn), 20);

		$transformDataRowsArray = function($optConnection = null) use ($paginatorComponent)
		{	
			$input = getProfessorsPartially($paginatorComponent->pageNum, 
											$paginatorComponent->numResultsOnPage, 
											($_GET["orderBy"] ?? ""), 
											($_GET["q"] ?? ""),
											$optConnection);
			$output = [];

			if($input)
			foreach ($input as $row)
			{
				$newRow = [];
				$newRow["id"] = $row["id"];
				$newRow["Nome"] = $row["name"];
				$newRow["E-mail"] = $row["email"];
							
				array_push($output, $newRow);
			}
			
			return $output;
		};
		
		$dataGridComponent = new DataGridComponent($transformDataRowsArray($conn));
		$dataGridComponent->columnsToHide[] = "id";
		$dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("professors", "view", "{param}");
		$dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL("professors", "edit", "{param}");
		$dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("professors", "delete", "{param}");
		
		$conn->close();
		
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
		require_once "model/database/terms.settings.database.php";

		$profId = isset($_GET['id']) && is_numeric($_GET['id']) ? $_GET['id'] : 0;

		$conn = createConnectionAsEditor();
		$profObject = null;
		$profPersonalDocs = null;
		$consentFormTermInfos = null;
		try
		{
			$profObject = new GenericObjectFromDataRow(getSingleProfessor($profId, $conn));

			$profObject->personalDocs = json_decode($profObject->personalDocsJson);
			$profObject->homeAddress = json_decode($profObject->homeAddressJson);
			$profObject->miniResume = json_decode($profObject->miniResumeJson);
			$profObject->bankData = json_decode($profObject->bankDataJson);

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
		try
		{
			$getter = new \SisEpi\Model\Professors\Professor();
			$getter->id = $profId;
			$getter->setCryptKey(getCryptoKey());
			$profObject = $getter->getSingle($conn);

			/*
			$profObject->personalDocs = json_decode($profObject->personalDocsJson);
			$profObject->homeAddress = json_decode($profObject->homeAddressJson);
			$profObject->miniResume = json_decode($profObject->miniResumeJson);
			$profObject->bankData = json_decode($profObject->bankDataJson);*/
		}
		catch (Exception $e)
		{
			$profObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }
		
		$this->view_PageData['profObject'] = $profObject;
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
        require_once("model/database/generalsettings.database.php");
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