<?php
require_once "vendor/autoload.php";
require_once "controller/component/DataGrid.class.php";
require_once "controller/component/Paginator.class.php";

use SisEpi\Model\Database\Connection;

class selectodsrelationClass extends PopupBasePage
{
	protected $title = "SisEPI - Selecionar relação ODS";
	
	protected $moduleName = "ODSRL";
	protected $permissionIdRequired = 1;
	
	private $dataGridComponent;
	private $paginatorComponent;
		
	protected function postConstruct()
	{
		require_once "controller/component/DataGrid.class.php";
		require_once "controller/component/Paginator.class.php";

		$conn = Connection::get();

        $paginatorComponent = null; 
        $dataGridComponent = null;

        try
        {
            $getter = new \SisEpi\Model\Ods\OdsRelation();
            $piecesCount = $getter->getCount($conn, $_GET['q'] ?? '');
            $paginatorComponent = new PaginatorComponent($piecesCount, 20);
            
            $odsRels = $getter->getMultiplePartially($conn, 
                                                    $paginatorComponent->pageNum,
                                                    $paginatorComponent->numResultsOnPage,
                                                    $_GET['orderBy'] ?? '',
                                                    $_GET['q'] ?? '');
            
            $transformRules =
            [
                'ID' => fn($r) => $r->id,
                'Nome' => fn($r) => $r->name,
                'Exercício' => fn($r) => $r->year,
                'Nº de metas' => fn($r) => $r->getOtherProperties()->goalsNumber
            ];

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($odsRels, $transformRules));
            $dataGridComponent->columnsToHide[] = 'ID';
            $dataGridComponent->RudButtonsFunctionParamName = 'ID';
            $dataGridComponent->selectButtonOnClick = "btnSelectOdsRelation_onClick(event, {param})";
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }
		
		$this->dataGridComponent = $dataGridComponent;
		$this->paginatorComponent = $paginatorComponent;
	}
	
	function render()
	{		
		$dgComp = $this->dataGridComponent;
		$pagComp = $this->paginatorComponent;
		
		$view = $this->get_view();		
		require_once($view);
	}
}