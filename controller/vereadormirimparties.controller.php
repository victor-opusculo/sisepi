<?php

require_once "model/Database/database.php";
require_once "vendor/autoload.php";

final class vereadormirimparties extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Vereador Mirim: Partidos";
		$this->subtitle = "Vereador Mirim: Partidos";
		
		$this->moduleName = "VMPTY";
		$this->permissionIdRequired = 1;
	}
	
	public function home()
	{
		require_once "controller/component/DataGrid.class.php";
		require_once("controller/component/Paginator.class.php");

		$conn = createConnectionAsEditor();
        $getter = new \SisEpi\Model\VereadorMirim\Party();

        $piecesCount = 0;
        $paginatorComponent = null; 
        $dataGridComponent = null;

        try
        {

            $piecesCount = $getter->getCount($conn, $_GET['q'] ?? '');
            $paginatorComponent = new PaginatorComponent($piecesCount, 20);
            
            $parties = $getter->getMultiplePartially($conn, 
                                                    $paginatorComponent->pageNum,
                                                    $paginatorComponent->numResultsOnPage,
                                                    $_GET['orderBy'] ?? '',
                                                    $_GET['q'] ?? '');
            
            $transformRules =
            [
                'id' => fn($p) => $p->id,
                'Nome' => fn($p) => $p->name,
                'Sigla' => fn($p) => $p->acronym
            ];

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($parties, $transformRules));
            $dataGridComponent->columnsToHide[] = 'id';
            $dataGridComponent->RudButtonsFunctionParamName = 'id';
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimparties', 'view', '{param}');
            $dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimparties', 'edit', '{param}');
            $dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimparties', 'delete', '{param}');
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
		$this->title = "SisEPI - Vereador Mirim: Criar partido";
		$this->subtitle = "Vereador Mirim: Criar partido";
		
		$this->moduleName = "VMPTY";
		$this->permissionIdRequired = 2;
	}

    public function create()
    { }

    public function pre_view()
	{
		$this->title = "SisEPI - Vereador Mirim: Ver partido";
		$this->subtitle = "Vereador Mirim: Ver partido";
		
		$this->moduleName = "VMPTY";
		$this->permissionIdRequired = 1;
	}

    public function view()
    {
        $partyId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $partyObject = null;
        $conn = createConnectionAsEditor();

        try
        {
            $getter = new \SisEpi\Model\VereadorMirim\Party();
            $getter->id = $partyId;
            $partyObject = $getter->getSingle($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['partyObj'] = $partyObject;
    }

    public function pre_edit()
	{
		$this->title = "SisEPI - Vereador Mirim: Editar partido";
		$this->subtitle = "Vereador Mirim: Editar partido";
		
		$this->moduleName = "VMPTY";
		$this->permissionIdRequired = 3;
	}

    public function edit()
    {
        $this->view();
    }

    public function pre_delete()
	{
		$this->title = "SisEPI - Vereador Mirim: Excluir partido";
		$this->subtitle = "Vereador Mirim: Excluir partido";
		
		$this->moduleName = "VMPTY";
		$this->permissionIdRequired = 4;
	}

    public function delete()
    {
        $this->edit();
    }
}