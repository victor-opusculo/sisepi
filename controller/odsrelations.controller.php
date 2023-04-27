<?php

use SisEpi\Model\Database\Connection;
use SisEpi\Model\Ods\OdsRelation;

require_once __DIR__ . '/../vendor/autoload.php';

final class odsrelations extends BaseController
{
    public function pre_home()
    {
        $this->title = "SisEPI - Relações ODS";
		$this->subtitle = "Relações ODS";
		
		$this->moduleName = "ODSRL";
		$this->permissionIdRequired = 1;
    }

    public function home()
    {
        require_once "controller/component/DataGrid.class.php";
        require_once "controller/component/Paginator.class.php";

        $conn = Connection::get();
        $dataGridComponent = null;
        $paginatorComponent = null;

        try
        {
            $getter = new OdsRelation();
            $count = $getter->getCount($conn, $_GET['q'] ?? '');

            $paginatorComponent = new PaginatorComponent($count, 20);
            $items = $getter->getMultiplePartially($conn, $paginatorComponent->pageNum, 
                                                            $paginatorComponent->numResultsOnPage, 
                                                            $_GET['orderBy'] ?? '',
                                                            $_GET['q'] ?? '');
            $dataGridComponent = new DataGridComponent(Data\transformDataRows($items, 
            [
                'ID' => fn($r) => $r->id,
                'Nome' => fn($r) => $r->name,
                'Exercício' => fn($r) => $r->year,
                'Nº de metas' => fn($r) => $r->getOtherProperties()->goalsNumber
            ]));

            $dataGridComponent->RudButtonsFunctionParamName = "ID";
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('odsrelations', 'view', '{param}');
            $dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL('odsrelations', 'edit', '{param}');
            $dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL('odsrelations', 'delete', '{param}');
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['dgComp'] = $dataGridComponent;
        $this->view_PageData['pagComp'] = $paginatorComponent;
    }

    public function pre_view()
    {
        $this->title = "SisEPI - Ver relação ODS";
		$this->subtitle = "Ver relação ODS";
		
		$this->moduleName = "ODSRL";
		$this->permissionIdRequired = 1;
    }

    public function view()
    {
        $relId = isset($_GET['id']) && Connection::isId($_GET['id']) ? $_GET['id'] : 0;

        $odsData = null;
        $odsRelation = null;

        $conn = Connection::get();
        try
        {
            $getter = new OdsRelation();
            $odsData = $getter->getOdsData($conn);
            $getter->id = $relId;
            $odsRelation = $getter->getSingle($conn);
            $odsRelation->fetchOdsAndGoalsStructured($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['odsData'] = $odsData;
        $this->view_PageData['odsRelation'] = $odsRelation;
    }

    public function pre_create()
    {
        $this->title = "SisEPI - Criar relação ODS";
		$this->subtitle = "Criar relação ODS";
		
		$this->moduleName = "ODSRL";
		$this->permissionIdRequired = 2;
    }

    public function create()
    {
        $odsData = null;
        $odsRelationModel = new OdsRelation();

        $conn = Connection::get();
        try
        {
            $getter = new OdsRelation();
            $odsData = $getter->getOdsData($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['mode'] = 'create';
        $this->view_PageData['odsData'] = $odsData;
        $this->view_PageData['odsRelation'] = $odsRelationModel;

        $this->action = 'edit'; //Use edit page template
    }

    public function pre_edit()
    {
        $this->title = "SisEPI - Editar relação ODS";
		$this->subtitle = "Editar relação ODS";
		
		$this->moduleName = "ODSRL";
		$this->permissionIdRequired = 3;
    }

    public function edit()
    {
		$relId = isset($_GET['id']) && Connection::isId($_GET['id']) ? $_GET['id'] : 0;

        $odsData = null;
        $odsRelation = null;

        $conn = Connection::get();
        try
        {
            $getter = new OdsRelation();
            $odsData = $getter->getOdsData($conn);
            $getter->id = $relId;
            $odsRelation = $getter->getSingle($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['odsData'] = $odsData;
        $this->view_PageData['odsRelation'] = $odsRelation;

        $this->view_PageData['mode'] = 'edit';

        $this->action = 'edit'; //Use edit page template
    }

    public function pre_delete()
    {
        $this->title = "SisEPI - Excluir relação ODS";
		$this->subtitle = "Excluir relação ODS";
		
		$this->moduleName = "ODSRL";
		$this->permissionIdRequired = 4;
    }

    public function delete()
    {
        $relId = isset($_GET['id']) && Connection::isId($_GET['id']) ? $_GET['id'] : 0;
        $odsRelation = null;

        $conn = Connection::get();
        try
        {
            $getter = new OdsRelation();
            $getter->id = $relId;
            $odsRelation = $getter->getSingle($conn);

        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['odsRelation'] = $odsRelation;
    }
}