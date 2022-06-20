<?php

require_once("model/GenericObjectFromDataRow.class.php");
require_once("model/database/eventsurveys.database.php");

final class eventsurveytemplates extends BaseController
{
    public function pre_home()
    {
        $this->title = "SisEPI - Modelos de Pesquisa de Satisfação";
		$this->subtitle = "Modelos de Pesquisa de Satisfação";
    }

    public function home()
    {
        require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");

		$conn = createConnectionAsEditor();
		
		$paginatorComponent = new PaginatorComponent(getEventSurveyTemplatesCount(($_GET["q"] ?? ""), $conn), 20);

		$templates = getEventSurveyTemplatesPartially($paginatorComponent->pageNum, 
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
		$dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL("eventsurveytemplates", "edit", "{param}");
		$dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("eventsurveytemplates", "delete", "{param}");
		
		$conn->close();
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
    }

    public function pre_edit()
    {
        $this->title = "SisEPI - Editar modelo de Pesquisa de Satisfação";
		$this->subtitle = "Editar modelo de Pesquisa de Satisfação";
		
		$this->moduleName = "SRVEY";
		$this->permissionIdRequired = 2;
    }

    public function edit()
    {
		$templateId = (int)$this->getActionData("id") ?? 0;
		$templateObj = null;
		$editSurveyPage = new eventsurveytemplates("editsurvey");

		$conn = createConnectionAsEditor();
		try
		{
			$templateObj = new GenericObjectFromDataRow(getSingleSurveyTemplate($templateId, $conn));
			$templateObj->templateJson = json_decode($templateObj->templateJson);
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

		$this->view_PageData['surveyObj'] = $templateObj->templateJson ?? null;
		$editSurveyPage->inheritViewPageData($this->view_PageData);
		$this->view_PageData['editSurveyPage'] = $editSurveyPage;
		$this->view_PageData['templateObj'] = $templateObj;
    }

	public function pre_create()
	{
		$this->title = "SisEPI - Criar modelo de Pesquisa de Satisfação";
		$this->subtitle = "Criar modelo de Pesquisa de Satisfação";
		
		$this->moduleName = "SRVEY";
		$this->permissionIdRequired = 1;
	}

	public function create()
	{
		require_once("model/DatabaseEntity.php");

		$templateObj = new DatabaseEntity("eventsurveytemplate", "new");
		$editSurveyPage = new eventsurveytemplates("editsurvey");

		$this->view_PageData['surveyObj'] = json_decode($templateObj->templateJson);
		$editSurveyPage->inheritViewPageData($this->view_PageData);
		$this->view_PageData['editSurveyPage'] = $editSurveyPage;
		$this->view_PageData['templateObj'] = $templateObj;
	}

	public function pre_delete()
	{
		$this->title = "SisEPI - Excluir modelo de Pesquisa de Satisfação";
		$this->subtitle = "Excluir modelo de Pesquisa de Satisfação";
		
		$this->moduleName = "SRVEY";
		$this->permissionIdRequired = 3;
	}

	public function delete()
	{
		$templateId = (int)$this->getActionData("id") ?? 0;
		$templateObj = null;

		$conn = createConnectionAsEditor();
		try
		{
			$templateObj = new GenericObjectFromDataRow(getSingleSurveyTemplate($templateId, $conn));
		}
		catch (Exception $e)
		{
			if (empty($_GET['messages']))
			{
				$this->pageMessages[] = $e->getMessage();
				$templateObj = null;
			}
		}
		finally
		{
			$conn->close();
		}

		$this->view_PageData['templateObj'] = $templateObj;
	}

	public function pre_editsurvey()
	{ }

	public function editsurvey()
	{ 
		if ($this->wasPageCalledDirectly())
			$this->pageMessages[] = "Erro: Esta página não pode ser aberta diretamente!";
	}
}