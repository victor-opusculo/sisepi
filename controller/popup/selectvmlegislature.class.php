<?php
require_once "model/Database/database.php";
require_once "vendor/autoload.php";
require_once "controller/component/DataGrid.class.php";
require_once "controller/component/Paginator.class.php";

class selectvmlegislatureClass extends PopupBasePage
{
	protected $title = "SisEPI - Vereador Mirim: Selecionar Legislatura";
	
	protected $moduleName = "VMLEG";
	protected $permissionIdRequired = 1;
	
	private $dataRows;
	private $dataGridComponent;
	private $paginatorComponent;
		
	protected function postConstruct()
	{
		$conn = createConnectionAsEditor();
		
		$getter = new \SisEpi\Model\VereadorMirim\Legislature();
		$this->paginatorComponent = new PaginatorComponent($getter->getCount($conn, $_GET['q'] ?? ''), 20);
		
		$legs = $getter->getMultiplePartially($conn,
													$this->paginatorComponent->pageNum,
													$this->paginatorComponent->numResultsOnPage,
													$_GET['orderBy'] ?? null,
													$_GET['q'] ?? '');
		$transformDataRowsRules =
		[
			'ID' => fn($t) => $t->id,
			'Nome' => fn($t) => $t->name,
			'Início' => fn($t) => date_create($t->begin)->format('d/m/Y'),
			'Fim' => fn($t) => date_create($t->end)->format('d/m/Y')
		];
		$this->dataRows = Data\transformDataRows($legs, $transformDataRowsRules);
		
		$this->dataGridComponent = new DataGridComponent($this->dataRows);
		$this->dataGridComponent->RudButtonsFunctionParamName = "ID";
		$this->dataGridComponent->selectButtonOnClick = "btnSelectLegislature_onClick(event, {param})";
		
		$conn->close();
	}
	
	function render()
	{		
		$dgComp = $this->dataGridComponent;
		$pagComp = $this->paginatorComponent;
		
		$view = $this->get_view();		
		require_once($view);
	}
}