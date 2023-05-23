<?php
require_once "vendor/autoload.php";
require_once "controller/component/DataGrid.class.php";
require_once "controller/component/Paginator.class.php";

use SisEpi\Model\Database\Connection;

class selecteventtesttemplateClass extends PopupBasePage
{
	protected $title = "SisEPI - Selecionar modelo de avaliação";
	
	protected $moduleName = "EVTST";
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
            $getter = new \SisEpi\Model\Events\EventTestTemplate();
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
            $dataGridComponent->RudButtonsFunctionParamName = 'ID';
            $dataGridComponent->selectButtonOnClick = "btnSelectTemplate_onClick(event, {param})";
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