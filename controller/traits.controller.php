<?php

use Model\Traits\EntityTrait;

require_once 'model/database/database.php';
require_once 'model/traits/EntityTrait.php';

final class traits extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Traços de eventos";
		$this->subtitle = "Traços de eventos";
		
		$this->moduleName = "TRAIT";
		$this->permissionIdRequired = 1;
	}
	
	public function home()
    {
        require_once 'controller/component/DataGrid.class.php';
        require_once 'controller/component/Paginator.class.php';

        $dataGridComponent = null;
        $paginatorComponent = null;

        $conn = createConnectionAsEditor();
        try
        {
            $getter = new EntityTrait();
            $paginatorComponent = new PaginatorComponent($getter->getCount($conn, $_GET['q'] ?? ''), 20);
            
            $traitsList = $getter->getMultiplePartially($conn, 
                                                        $paginatorComponent->pageNum,
                                                        $paginatorComponent->numResultsOnPage,
                                                        $_GET['orderBy'] ?? '',
                                                        $_GET['q'] ?? '');
            $transformRules = 
            [
                'ID' => fn($t) => $t->id,
                'Nome' => fn($t) => $t->name,
                'Ícone' => fn($t) => new DataGridIcon("uploads/traits/{$t->id}.{$t->fileExtension}", $t->name, null, null, 32)
            ];

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($traitsList, $transformRules));
            $dataGridComponent->RudButtonsFunctionParamName = 'ID';
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('traits', 'view', '{param}');
            $dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL('traits', 'edit', '{param}');
            $dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL('traits', 'delete', '{param}');
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally{ $conn->close(); }

        $this->view_PageData['dgComp'] = $dataGridComponent;
        $this->view_PageData['pagComp'] = $paginatorComponent;
    }

    public function pre_view()
    {
        $this->title = "SisEPI - Ver traço";
		$this->subtitle = "Ver traço";
		
		$this->moduleName = "TRAIT";
		$this->permissionIdRequired = 1;
    }

    public function view()
    {
        $traitId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : 0;

        $conn = createConnectionAsEditor();
        $traitObject = null;
        try
        {
            $getter = new EntityTrait();
            $getter->id = $traitId;

            $traitObject = $getter->getSingle($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally{ $conn->close(); }

        $this->view_PageData['traitObj'] = $traitObject;
    }

    public function pre_create()
    {
        $this->title = "SisEPI - Criar traço";
		$this->subtitle = "Criar traço";
		
		$this->moduleName = "TRAIT";
		$this->permissionIdRequired = 2;
    }

    public function create() { }

    public function pre_edit()
    {
        $this->title = "SisEPI - Editar traço";
		$this->subtitle = "Editar traço";
		
		$this->moduleName = "TRAIT";
		$this->permissionIdRequired = 3;
    }

    public function edit()
    {
        $this->view();   
    }

    public function pre_delete()
    {
        $this->title = "SisEPI - Excluir traço";
		$this->subtitle = "Excluir traço";
		
		$this->moduleName = "TRAIT";
		$this->permissionIdRequired = 4;
    }

    public function delete()
    {
        $this->view();   
    }
}