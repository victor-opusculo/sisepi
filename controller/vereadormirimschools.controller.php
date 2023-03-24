<?php

require_once "vendor/autoload.php";

use SisEpi\Model\Database\Connection;

final class vereadormirimschools extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Vereador Mirim: Escolas";
		$this->subtitle = "Vereador Mirim: Escolas";
		
		$this->moduleName = "VMSCH";
		$this->permissionIdRequired = 1;
	}
	
	public function home()
	{
		require_once "controller/component/DataGrid.class.php";
		require_once "controller/component/Paginator.class.php";

		$conn = Connection::get();

        $paginatorComponent = null; 
        $dataGridComponent = null;

        try
        {
            $getter = new \SisEpi\Model\VereadorMirim\School();
            $getter->setCryptKey(Connection::getCryptoKey());
            $piecesCount = $getter->getCount($conn, $_GET['q'] ?? '');
            $paginatorComponent = new PaginatorComponent($piecesCount, 20);
            
            $schools = $getter->getMultiplePartially($conn, 
                                                    $paginatorComponent->pageNum,
                                                    $paginatorComponent->numResultsOnPage,
                                                    $_GET['orderBy'] ?? '',
                                                    $_GET['q'] ?? '');
            
            $transformRules =
            [
                'id' => fn($s) => $s->id,
                'Nome da Escola' => fn($s) => $s->name,
                'Diretor(a)' => fn($s) => $s->directorName,
                'E-mail corporativo' => fn($s) => $s->email,
                'Data de registro' => fn($s) => date_create($s->registrationDate)->format('d/m/Y H:i:s')
            ];

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($schools, $transformRules));
            $dataGridComponent->columnsToHide[] = 'id';
            $dataGridComponent->RudButtonsFunctionParamName = 'id';
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimschools', 'view', '{param}');
            $dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimschools', 'edit', '{param}');
            $dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimschools', 'delete', '{param}');
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
		$this->title = "SisEPI - Vereador Mirim: Criar escola";
		$this->subtitle = "Vereador Mirim: Criar escola";
		
		$this->moduleName = "VMSCH";
		$this->permissionIdRequired = 2;
	}

    public function create()
    { }

    public function pre_view()
	{
		$this->title = "SisEPI - Vereador Mirim: Ver escola";
		$this->subtitle = "Vereador Mirim: Ver escola";
		
		$this->moduleName = "VMSCH";
		$this->permissionIdRequired = 1;
	}

    public function view()
    {
        $schoolId = isset($_GET['id']) && Connection::isId($_GET['id']) ? $_GET['id'] : null;

        $schoolObject = null;
        $conn = Connection::get();

        try
        {
            $getter = new \SisEpi\Model\VereadorMirim\School();
            $getter->id = $schoolId;
            $getter->setCryptKey(Connection::getCryptoKey());
            $schoolObject = $getter->getSingle($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['schoolObj'] = $schoolObject;
    }

    public function pre_edit()
	{
		$this->title = "SisEPI - Vereador Mirim: Editar escola";
		$this->subtitle = "Vereador Mirim: Editar escola";
		
		$this->moduleName = "VMSCH";
		$this->permissionIdRequired = 3;
	}

    public function edit()
    {
        $this->view();
    }

    public function pre_delete()
	{
		$this->title = "SisEPI - Vereador Mirim: Excluir escola";
		$this->subtitle = "Vereador Mirim: Excluir escola";
		
		$this->moduleName = "VMSCH";
		$this->permissionIdRequired = 4;
	}

    public function delete()
    {
        $this->edit();
    }
}