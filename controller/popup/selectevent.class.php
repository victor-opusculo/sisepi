<?php
require_once("model/database/events.database.php");
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
		
		$this->paginatorComponent = new PaginatorComponent(getEventsCount(($_GET["q"] ?? ""), $conn), 20);
		
		$partialEventsDrs = getEventsPartially($this->paginatorComponent->pageNum,
												$this->paginatorComponent->numResultsOnPage,
												($_GET['orderBy'] ?? null),
												($_GET['q'] ?? ''),
												$conn);

		$transformDataRowsRules =
		[
			'ID' => fn($dr) => $dr['id'],
			'Tipo' => fn($dr) => $dr['typeName'],
			'Nome' => fn($dr) => $dr['name'],
			'Modalidade' => fn($dr) => Data\getEventMode($dr['locTypes']),
			'Data de início' => fn($dr) => $dr['date']
		];
		$this->dataRows = Data\transformDataRows($partialEventsDrs, $transformDataRowsRules);
		
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