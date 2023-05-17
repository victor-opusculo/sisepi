<?php

use SisEpi\Model\Database\Connection;
use SisEpi\Model\Events\EventTestTemplate;

require_once __DIR__ . '/../vendor/autoload.php';

final class eventtesttemplates extends BaseController
{
    public function pre_home()
    {
        $this->title = "SisEPI - Modelos de avaliações";
		$this->subtitle = "Modelos de avaliações";

		$this->moduleName = "EVTST";
		$this->permissionIdRequired = 1;
    }

    public function home()
    {
        require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");

		$conn = Connection::get();
		try
		{
			$getter = new EventTestTemplate();
			$paginatorComponent = new PaginatorComponent($getter->getCount($conn, ($_GET["q"] ?? "")), 20);

			$templates = $getter->getMultiplePartially($conn, 
												$paginatorComponent->pageNum, 
												$paginatorComponent->numResultsOnPage, 
												$_GET['orderBy'] ?? '',
												($_GET["q"] ?? ""));

			$outputDataRows = Data\transformDataRows($templates, 
			[
				'ID' => fn($r) => $r->id,
				'Nome' => fn($r) => $r->name
			]);
			
			$dataGridComponent = new DataGridComponent($outputDataRows);
			$dataGridComponent->RudButtonsFunctionParamName = "ID";
			$dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL("eventtesttemplates", "edit", "{param}");
			$dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("eventtesttemplates", "delete", "{param}");
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}

		$conn->close();
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
    }

    public function pre_edit()
    {
        $this->title = "SisEPI - Editar modelo de avaliação";
		$this->subtitle = "Editar modelo de avaliação";
		
		$this->moduleName = "EVTST";
		$this->permissionIdRequired = 3;
    }

    public function edit()
    {
		$templateId = (int)$this->getActionData("id") ?? 0;
		$templateObj = null;
		$templateDbEntity = null;
		$editTestPage = new eventtesttemplates("edittest");

		$conn = Connection::get();
		try
		{
			$getter = new EventTestTemplate();
			$getter->id = $templateId;
			$templateDbEntity = $getter->getSingle($conn);
			$templateObj = json_decode($templateDbEntity->templateJson);
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
			$templateObj = null;
			$templateDbEntity = null;
		}
		finally { $conn->close(); }

		$this->view_PageData['testObj'] = $templateObj ?? null;
		$editTestPage->inheritViewPageData($this->view_PageData);
		$this->view_PageData['editTestPage'] = $editTestPage;
		$this->view_PageData['templateDbEntity'] = $templateDbEntity;
    }

	public function pre_create()
	{
		$this->title = "SisEPI - Criar modelo de avaliação";
		$this->subtitle = "Criar modelo de avaliação";
		
		$this->moduleName = "EVTST";
		$this->permissionIdRequired = 2;
	}

	public function create()
	{
		$templateDbEntity = new EventTestTemplate;
		$templateDbEntity->fillPropertiesWithDefaultValues();
		$editTestPage = new eventtesttemplates("edittest");

		$this->view_PageData['testObj'] = json_decode($templateDbEntity->templateJson);
		$editTestPage->inheritViewPageData($this->view_PageData);
		$this->view_PageData['editTestPage'] = $editTestPage;
		$this->view_PageData['templateDbEntity'] = $templateDbEntity;
	}

	public function pre_delete()
	{
		$this->title = "SisEPI - Excluir modelo de avaliação";
		$this->subtitle = "Excluir modelo de avaliação";
		
		$this->moduleName = "EVTST";
		$this->permissionIdRequired = 4;
	}

	public function delete()
	{
		$templateId = (int)$this->getActionData("id") ?? 0;
		$templateDbEntity = null;

		$conn = Connection::get();
		try
		{
			$getter = new EventTestTemplate;
			$getter->id = $templateId;
			$templateDbEntity = $getter->getSingle($conn);
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
			$templateDbEntity = null;
		}
		finally { $conn->close(); }

		$this->view_PageData['templateDbEntity'] = $templateDbEntity;
	}

	public function pre_edittest()
	{ }

	public function edittest()
	{ 
		if ($this->wasPageCalledDirectly())
			$this->pageMessages[] = "Erro: Esta página não pode ser aberta diretamente!";
	}
}