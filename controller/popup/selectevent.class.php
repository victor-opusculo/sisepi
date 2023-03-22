<?php
require_once("model/database/events.database.php");
require_once "model/events/Event.php";
require_once("controller/component/DataGrid.class.php");
require_once("controller/component/Paginator.class.php");

class selecteventClass extends PopupBasePage
{
	protected $title = "SisEPI - Selecionar Evento";
	
	protected $moduleName = "EVENT";
	protected $permissionIdRequired = 4;
	
	private $dataRows;
	private $dataGridComponent;
	private $paginatorComponent;
		
	protected function postConstruct()
	{
		$conn = createConnectionAsEditor();
		
		$getter = new \SisEpi\Model\Events\Event();

		$this->paginatorComponent = new PaginatorComponent($getter->getCount($conn, $_GET['q'] ?? ""), 20);
		
		$partialEvents = $getter->getMultiplePartially($conn,
															$this->paginatorComponent->pageNum,
															$this->paginatorComponent->numResultsOnPage,
															$_GET['orderBy'] ?? "",
															$_GET['q'] ?? "");
		$transformDataRowsRules =
		[
			'ID' => fn($dr) => $dr->id,
			'Tipo' => fn($dr) => $dr->getOtherProperties()->typeName,
			'Nome' => fn($dr) => $dr->name,
			'Modalidade' => fn($dr) => Data\getEventMode($dr->getOtherProperties()->locTypes),
			'Data de início' => fn($dr) => date_create($dr->getOtherProperties()->date)->format('d/m/Y')
		];
		$this->dataRows = Data\transformDataRows($partialEvents, $transformDataRowsRules);
		
		$this->dataGridComponent = new DataGridComponent($this->dataRows);
		$this->dataGridComponent->RudButtonsFunctionParamName = "ID";
		$this->dataGridComponent->selectButtonOnClick = "btnSelectEvent_onClick(event, {param})";
		
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