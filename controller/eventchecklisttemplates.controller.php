<?php

require_once("model/GenericObjectFromDataRow.class.php");
require_once("model/database/eventchecklists.database.php");

final class eventchecklisttemplates extends BaseController
{
    public function pre_home()
    {
        $this->title = "SisEPI - Modelos de Checklist";
		$this->subtitle = "Modelos de Checklist";
    }

    public function home()
    {
        require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");

		$conn = createConnectionAsEditor();
		
		$paginatorComponent = new PaginatorComponent(getEventChecklistTemplatesCount(($_GET["q"] ?? ""), $conn), 20);

		$templates = getEventChecklistTemplatesPartially($paginatorComponent->pageNum, 
											$paginatorComponent->numResultsOnPage, 
											($_GET["q"] ?? ""),
											$conn);

		$outputDataRows = Data\transformDataRows($templates, 
		[
			'id' => fn($r) => $r['id'],
			'Nome' => fn($r) => $r['name']
		]);
		
		$dataGridComponent = new DataGridComponent($outputDataRows);
		$dataGridComponent->columnsToHide[] = "id";
		$dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL("eventchecklisttemplates", "edit", "{param}");
		$dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("eventchecklisttemplates", "delete", "{param}");
		
		$conn->close();
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
    }

    public function pre_edit()
    {
        $this->title = "SisEPI - Editar modelo de Checklist";
		$this->subtitle = "Editar modelo de Checklist";
		
		$this->moduleName = "CHKLS";
		$this->permissionIdRequired = 1;
    }

    public function edit()
    {
		$templateId = (int)$this->getActionData("id") ?? 0;
		$templateObj = null;
		$responsibleUsersList = null;
		$editChecklistPage = new eventchecklisttemplates("editchecklist");

		$conn = createConnectionAsEditor();
		try
		{
			$templateObj = new GenericObjectFromDataRow(getSingleChecklistTemplate($templateId, $conn));
			$templateObj->templateJson = json_decode($templateObj->templateJson);
			$responsibleUsersList = getResponsibleUsersList($conn);
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
			$templateObj = null;
		}
		finally
		{
			$conn->close();
		}

		//$this->view_PageData['operation'] = 'edit';
		$this->view_PageData['responsibleUsersList'] = $responsibleUsersList;
		$this->view_PageData['checklistObj'] = $templateObj->templateJson;
		$editChecklistPage->inheritViewPageData($this->view_PageData);
		$this->view_PageData['editChecklistPage'] = $editChecklistPage;
		$this->view_PageData['templateObj'] = $templateObj;
    }

	public function pre_create()
	{
		$this->title = "SisEPI - Criar modelo de Checklist";
		$this->subtitle = "Criar modelo de Checklist";
		
		$this->moduleName = "CHKLS";
		$this->permissionIdRequired = 1;
	}

	public function create()
	{
		require_once("model/DatabaseEntity.php");

		$responsibleUsersList = getResponsibleUsersList();
		$templateObj = new DatabaseEntity("eventchecklisttemplate", "new");
		$editChecklistPage = new eventchecklisttemplates("editchecklist");

		$this->view_PageData['responsibleUsersList'] = $responsibleUsersList;
		$this->view_PageData['checklistObj'] = json_decode($templateObj->templateJson);
		$editChecklistPage->inheritViewPageData($this->view_PageData);
		$this->view_PageData['editChecklistPage'] = $editChecklistPage;
		$this->view_PageData['templateObj'] = $templateObj;
	}

	public function pre_delete()
	{
		$this->title = "SisEPI - Excluir modelo de Checklist";
		$this->subtitle = "Excluir modelo de Checklist";
		
		$this->moduleName = "CHKLS";
		$this->permissionIdRequired = 2;
	}

	public function delete()
	{
		$templateId = (int)$this->getActionData("id") ?? 0;
		$templateObj = null;

		$conn = createConnectionAsEditor();
		try
		{
			$templateObj = new GenericObjectFromDataRow(getSingleChecklistTemplate($templateId, $conn));
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
			$templateObj = null;
		}
		finally
		{
			$conn->close();
		}

		$this->view_PageData['templateObj'] = $templateObj;
	}

	protected function pre_editchecklist()
	{
		$this->moduleName = "CHKLS";
		$this->permissionIdRequired = 1;
	}

	protected function editchecklist()
	{
	}
}