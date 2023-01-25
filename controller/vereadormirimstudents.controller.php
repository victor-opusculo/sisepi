<?php

require_once "model/database/database.php";
require_once "model/vereadormirim/Student.php";

final class vereadormirimstudents extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Vereador Mirim: Vereadores";
		$this->subtitle = "Vereador Mirim: Vereadores";
		
		$this->moduleName = "VMSTU";
		$this->permissionIdRequired = 1;
	}
	
	public function home()
	{
		require_once "controller/component/DataGrid.class.php";
		require_once "controller/component/Paginator.class.php";

		$conn = createConnectionAsEditor();
        $getter = new \Model\VereadorMirim\Student();
        $getter->setCryptKey(getCryptoKey());

        $piecesCount = 0;
        $paginatorComponent = null; 
        $dataGridComponent = null;

        try
        {

            $piecesCount = $getter->getCount($conn, $_GET['q'] ?? '');
            $paginatorComponent = new PaginatorComponent($piecesCount, 20);
            
            $vmStudents = $getter->getMultiplePartially($conn, 
                                                    $paginatorComponent->pageNum,
                                                    $paginatorComponent->numResultsOnPage,
                                                    $_GET['orderBy'] ?? '',
                                                    $_GET['q'] ?? '');
            $transformRules =
            [
                'id' => fn($s) => $s->id,
                'Nome' => fn($s) => $s->name,
                'E-mail' => fn($s) => $s->email,
                'Legislatura' => fn($s) => $s->getOtherProperties()->legislatureName
            ];

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($vmStudents, $transformRules));
            $dataGridComponent->columnsToHide[] = 'id';
            $dataGridComponent->RudButtonsFunctionParamName = 'id';
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimstudents', 'view', '{param}');
            $dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimstudents', 'edit', '{param}');
            $dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimstudents', 'delete', '{param}');
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
		$this->title = "SisEPI - Vereador Mirim: Criar vereador";
		$this->subtitle = "Vereador Mirim: Criar vereador";
		
		$this->moduleName = "VMSTU";
		$this->permissionIdRequired = 2;
	}

    public function create() 
    {
        require_once "model/vereadormirim/Party.php";

        $conn = createConnectionAsEditor();
        $partiesList = null;
        try
        {
            $partiesGetter = new \Model\VereadorMirim\Party();
            $partiesList = $partiesGetter->getAllBasic($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['partiesList'] = $partiesList;
    }

    public function pre_view()
    {
        $this->title = "SisEPI - Vereador Mirim: Ver vereador";
		$this->subtitle = "Vereador Mirim: Ver vereador";
		
		$this->moduleName = "VMSTU";
		$this->permissionIdRequired = 1;
    }

    public function view()
    {
        $studentId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $vmStudentObject = null;
        $conn = createConnectionAsEditor();

        try
        {
            $getter = new \Model\VereadorMirim\Student();
            $getter->setCryptKey(getCryptoKey());
            $getter->id = $studentId;
            $vmStudentObject = $getter->getSingle($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['vmStudentObj'] = $vmStudentObject;
    }

    public function pre_edit()
    {
        $this->title = "SisEPI - Vereador Mirim: Editar vereador";
		$this->subtitle = "Vereador Mirim: Editar vereador";
		
		$this->moduleName = "VMSTU";
		$this->permissionIdRequired = 3;
    }

    public function edit()
    {
        require_once "model/vereadormirim/Party.php";

        $studentId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $vmStudentObject = null;
        $partiesList = null;
        $conn = createConnectionAsEditor();

        try
        {
            $getter = new \Model\VereadorMirim\Student();
            $getter->setCryptKey(getCryptoKey());
            $getter->id = $studentId;
            $vmStudentObject = $getter->getSingle($conn);

            $partiesGetter = new \Model\VereadorMirim\Party();
            $partiesList = $partiesGetter->getAllBasic($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['vmStudentObj'] = $vmStudentObject;
        $this->view_PageData['partiesList'] = $partiesList;
    }

    public function pre_delete()
    {
        $this->title = "SisEPI - Vereador Mirim: Excluir vereador";
		$this->subtitle = "Vereador Mirim: Excluir vereador";
		
		$this->moduleName = "VMSTU";
		$this->permissionIdRequired = 4;
    }

    public function delete()
    {
        $this->view();
    }
}