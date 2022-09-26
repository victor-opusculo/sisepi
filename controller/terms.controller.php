<?php
require_once "model/database/terms.settings.database.php";

final class terms extends BaseController
{
    public function pre_home()
	{
		$this->title = "SisEPI - Configurações: Termos";
		$this->subtitle = "Termos";
		
		$this->moduleName = "TERMS";
		$this->permissionIdRequired = 1;
	}

	public function home()
	{
		require_once "controller/component/DataGrid.class.php";
		require_once "controller/component/Paginator.class.php";

		$conn = createConnectionAsEditor();

		$paginatorComponent = null;
		$dataGridComponent = null;
		try
		{
			$paginatorComponent = new PaginatorComponent(getTermsCount(($_GET["q"] ?? ""), $conn), 20);
			$termsDrs = getTermsPartially($paginatorComponent->pageNum,
											$paginatorComponent->numResultsOnPage,
											$_GET['q'] ?? "",
											$_GET['orderBy'] ?? "",
											$conn);
			
			$termsDrsTransformRules =
			[
				'ID' => fn($r) => $r['id'],
				'Nome' => fn($r) => $r['name'],
				'Versão' => fn($r) => $r['version'],
				'Data de registro' => fn($r) => date_create($r['registrationDate'])->format('d/m/Y H:i:s')
			];

			$dataGridComponent = new DataGridComponent(Data\transformDataRows($termsDrs, $termsDrsTransformRules));
			$dataGridComponent->RudButtonsFunctionParamName = "ID";
			$dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL("terms", "edit", "{param}");
			$dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("terms", "delete", "{param}");
			$dataGridComponent->customButtons['Visualizar termo'] = URL\URLGenerator::generateFileURL('uploads/terms/{termId}.pdf');
			$dataGridComponent->customButtonsParameters['termId'] = 'ID';
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }

		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
	}

    public function pre_create()
    {
        $this->title = "SisEPI - Configurações: Novo termo";
		$this->subtitle = "Novo termo";
		
		$this->moduleName = "TERMS";
		$this->permissionIdRequired = 2;
    }

    public function create()
    { }

    public function pre_edit()
	{
		$this->title = "SisEPI - Configurações: Editar termo";
		$this->subtitle = "Editar termo";
		
		$this->moduleName = "TERMS";
		$this->permissionIdRequired = 3;
	}

	public function edit()
	{
		require_once "model/GenericObjectFromDataRow.class.php";

		$termId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
		$termObject = null;
		try
		{
			$termObject = new GenericObjectFromDataRow(getSingleTerm($termId));
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}

		$this->view_PageData['termObj'] = $termObject;
	}

	public function pre_delete()
	{
		$this->title = "SisEPI - Configurações: Excluir termo";
		$this->subtitle = "Excluir termo";
		
		$this->moduleName = "TERMS";
		$this->permissionIdRequired = 4;
	}

	public function delete()
	{
		$this->edit();
	}
}