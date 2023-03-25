<?php
require_once "model/Database/database.php";
require_once "vendor/autoload.php";
require_once "controller/component/DataGrid.class.php";
require_once "controller/component/Paginator.class.php";

class selecttraitClass extends PopupBasePage
{
	protected $title = "SisEPI - Selecionar Traço";
	
	protected $moduleName = "TRAIT";
	protected $permissionIdRequired = 1;
	
	private $dataRows;
	private $dataGridComponent;
	private $paginatorComponent;
		
	protected function postConstruct()
	{
		$conn = createConnectionAsEditor();
		
		$getter = new \SisEpi\Model\Traits\EntityTrait();
		$this->paginatorComponent = new PaginatorComponent($getter->getCount($conn, $_GET['q'] ?? ''), 20);
		
		$traitsList = $getter->getMultiplePartially($conn,
													$this->paginatorComponent->pageNum,
													$this->paginatorComponent->numResultsOnPage,
													$_GET['orderBy'] ?? null,
													$_GET['q'] ?? '');
		$transformDataRowsRules =
		[
			'ID' => fn($t) => $t->id,
			'Nome' => fn($t) => $t->name,
			'Ícone' => fn($t) => new DataGridIcon("uploads/traits/{$t->id}.{$t->fileExtension}", $t->name, null, null, 32)
		];
		$this->dataRows = Data\transformDataRows($traitsList, $transformDataRowsRules);
		
		$this->dataGridComponent = new DataGridComponent($this->dataRows);
		$this->dataGridComponent->RudButtonsFunctionParamName = "ID";
		$this->dataGridComponent->selectButtonOnClick = "btnSelectTrait_onClick(event, {param})";
		
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