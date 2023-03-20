<?php

require_once "model/database/database.php";
require_once "model/vereadormirim/Legislature.php";

final class vereadormirimlegislatures extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Vereador Mirim: Legislaturas";
		$this->subtitle = "Vereador Mirim: Legislaturas";
		
		$this->moduleName = "VMLEG";
		$this->permissionIdRequired = 1;
	}
	
	public function home()
	{
		require_once "controller/component/DataGrid.class.php";
		require_once("controller/component/Paginator.class.php");

		$conn = createConnectionAsEditor();
        $getter = new \Model\VereadorMirim\Legislature();

        $piecesCount = 0;
        $paginatorComponent = null; 
        $dataGridComponent = null;

        try
        {

            $piecesCount = $getter->getCount($conn, $_GET['q'] ?? '');
            $paginatorComponent = new PaginatorComponent($piecesCount, 20);
            
            $legs = $getter->getMultiplePartially($conn, 
                                                    $paginatorComponent->pageNum,
                                                    $paginatorComponent->numResultsOnPage,
                                                    $_GET['orderBy'] ?? '',
                                                    $_GET['q'] ?? '');
            
            $transformRules =
            [
                'id' => fn($l) => $l->id,
                'Nome' => fn($l) => $l->name,
                'Início' => fn($l) => date_create($l->begin)->format('d/m/Y'),
                'Fim' => fn($l) => date_create($l->end)->format('d/m/Y')
            ];

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($legs, $transformRules));
            $dataGridComponent->columnsToHide[] = 'id';
            $dataGridComponent->RudButtonsFunctionParamName = 'id';
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimlegislatures', 'view', '{param}');
            $dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimlegislatures', 'edit', '{param}');
            $dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimlegislatures', 'delete', '{param}');
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
		$this->title = "SisEPI - Vereador Mirim: Criar legislatura";
		$this->subtitle = "Vereador Mirim: Criar legislatura";
		
		$this->moduleName = "VMLEG";
		$this->permissionIdRequired = 2;
	}

    public function create() { }

    public function pre_view()
	{
		$this->title = "SisEPI - Vereador Mirim: Ver legislatura";
		$this->subtitle = "Vereador Mirim: Ver legislatura";
		
		$this->moduleName = "VMLEG";
		$this->permissionIdRequired = 1;
	}

    public function view()
    {
        require_once "controller/component/DataGrid.class.php";
		require_once("controller/component/Paginator.class.php");
        require_once "model/vereadormirim/Student.php";

        $legId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
        $conn = createConnectionAsEditor();

        $legislatureObject = null;
        $electedDataGrid = null;
        try
        {
            $getter = new \Model\VereadorMirim\Legislature();
            $getter->id = $legId;
            $legislatureObject = $getter->getSingle($conn);

            $getter = new \Model\VereadorMirim\Student();
            $getter->vmLegislatureId = $legId;
            $getter->setCryptKey(getCryptoKey());
            $elected = $getter->getAllElectedFromLegislature($conn);

            $electedDataGrid = new DataGridComponent(Data\transformDataRows($elected,
            [
                'id' => fn($s) => $s->id,
                'Nome' => fn($s) => $s->name,
                'E-mail' => fn($s) => $s->email,
                'Status' => fn($s) => (bool)$s->isActive ? 'Ativo' : 'Desativado'
            ]));
            $electedDataGrid->columnsToHide[] = 'id';
            $electedDataGrid->RudButtonsFunctionParamName = 'id';
            $electedDataGrid->detailsButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimstudents', 'view', '{param}');
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['legislatureObj'] = $legislatureObject;
        $this->view_PageData['electedDgComp'] = $electedDataGrid;

    }

    public function pre_edit()
	{
		$this->title = "SisEPI - Vereador Mirim: Editar legislatura";
		$this->subtitle = "Vereador Mirim: Editar legislatura";
		
		$this->moduleName = "VMLEG";
		$this->permissionIdRequired = 3;
	}

    public function edit()
    {
        $legId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
        $conn = createConnectionAsEditor();

        $legislatureObject = null;
        try
        {
            $getter = new \Model\VereadorMirim\Legislature();
            $getter->id = $legId;
            $legislatureObject = $getter->getSingle($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['legislatureObj'] = $legislatureObject;
    }

    public function pre_delete()
	{
		$this->title = "SisEPI - Vereador Mirim: Excluir legislatura";
		$this->subtitle = "Vereador Mirim: Excluir legislatura";
		
		$this->moduleName = "VMLEG";
		$this->permissionIdRequired = 4;
	}

    public function delete()
    {
        $this->edit();
    }
}