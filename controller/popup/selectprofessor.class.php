<?php
require_once("model/database/professors.database.php");
require_once("controller/component/DataGrid.class.php");
require_once("controller/component/Paginator.class.php");

class selectprofessorClass extends PopupBasePage
{
	protected $title = "SisEPI - Selecionar docente";
	
	protected $moduleName = "PROFE";
	protected $permissionIdRequired = 1;
	
	private $dataRows;
	private $dataGridComponent;
	private $paginatorComponent;
		
	protected function postConstruct()
	{
		$conn = createConnectionAsEditor();
		
		$this->paginatorComponent = new PaginatorComponent(getProfessorsCount(($_GET["q"] ?? ""), $conn), 20);
		
		$partialProfessorsDrs = getProfessorsPartially($this->paginatorComponent->pageNum,
												$this->paginatorComponent->numResultsOnPage,
												($_GET['orderBy'] ?? null),
												($_GET['q'] ?? ''),
												$conn);

		$transformDataRowsRules =
		[
			'ID' => fn($dr) => $dr['id'],
			'Nome' => fn($dr) => $dr['name'],
			'E-mail' => fn($dr) => $dr['email']
		];
		$this->dataRows = Data\transformDataRows($partialProfessorsDrs, $transformDataRowsRules);
		
		$this->dataGridComponent = new DataGridComponent($this->dataRows);
		$this->dataGridComponent->RudButtonsFunctionParamName = "ID";
		$this->dataGridComponent->selectButtonOnClick = "btnSelectProfessor_onClick(event, {param})";
		
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