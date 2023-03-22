<?php

require_once "model/database/database.php";
require_once "model/vereadormirim/VmParent.php";

final class vereadormirimparents extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Vereador Mirim: Pais/Responsáveis";
		$this->subtitle = "Vereador Mirim: Pais/Responsáveis";
		
		$this->moduleName = "VMPAR";
		$this->permissionIdRequired = 1;
	}
	
	public function home()
	{
		require_once "controller/component/DataGrid.class.php";
		require_once("controller/component/Paginator.class.php");

		$conn = createConnectionAsEditor();
        $getter = new \SisEpi\Model\VereadorMirim\VmParent();
        $getter->setCryptKey(getCryptoKey());

        $piecesCount = 0;
        $paginatorComponent = null; 
        $dataGridComponent = null;

        try
        {

            $piecesCount = $getter->getCount($conn, $_GET['q'] ?? '');
            $paginatorComponent = new PaginatorComponent($piecesCount, 20);
            
            $parents = $getter->getMultiplePartially($conn, 
                                                    $paginatorComponent->pageNum,
                                                    $paginatorComponent->numResultsOnPage,
                                                    $_GET['orderBy'] ?? '',
                                                    $_GET['q'] ?? '');
            
            $transformRules =
            [
                'id' => fn($p) => $p->id,
                'Nome' => fn($p) => $p->name,
                'E-mail' => fn($p) => $p->email
            ];

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($parents, $transformRules));
            $dataGridComponent->columnsToHide[] = 'id';
            $dataGridComponent->RudButtonsFunctionParamName = 'id';
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimparents', 'view', '{param}');
            $dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimparents', 'edit', '{param}');
            $dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimparents', 'delete', '{param}');
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
		$this->title = "SisEPI - Vereador Mirim: Criar responsável";
		$this->subtitle = "Vereador Mirim: Criar responsável";
		
		$this->moduleName = "VMPAR";
		$this->permissionIdRequired = 2;
	}

    public function create()
    { }

    public function pre_view()
	{
		$this->title = "SisEPI - Vereador Mirim: Ver responsável";
		$this->subtitle = "Vereador Mirim: Ver responsável";
		
		$this->moduleName = "VMPAR";
		$this->permissionIdRequired = 1;
	}

    public function view()
    {
        $parentId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $parentObject = null;
        $conn = createConnectionAsEditor();

        try
        {
            $getter = new \SisEpi\Model\VereadorMirim\VmParent();
            $getter->id = $parentId;
            $getter->setCryptKey(getCryptoKey());
            $parentObject = $getter->getSingle($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['parentObj'] = $parentObject;
    }

    public function pre_edit()
	{
		$this->title = "SisEPI - Vereador Mirim: Editar responsável";
		$this->subtitle = "Vereador Mirim: Editar responsável";
		
		$this->moduleName = "VMPAR";
		$this->permissionIdRequired = 3;
	}

    public function edit()
    {
        $this->view();
    }

    public function pre_delete()
	{
		$this->title = "SisEPI - Vereador Mirim: Excluir responsável";
		$this->subtitle = "Vereador Mirim: Excluir responsável";
		
		$this->moduleName = "VMPAR";
		$this->permissionIdRequired = 4;
	}

    public function delete()
    {
        $this->edit();
    }
}