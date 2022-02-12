<?php
require_once("model/database/libraryusers.database.php");
require_once("controller/component/DataGrid.class.php");
require_once("controller/component/Paginator.class.php");

class libselectuserClass extends PopupBasePage
{
	protected $title = "SisEPI - Biblioteca: Selecionar usuário";
	
	protected $moduleName = "LIBR";
	protected $permissionIdRequired = 5;
	
	private $dataRows;
	private $dataGridComponent;
	private $paginatorComponent;
		
	protected function postConstruct()
	{
		$conn = createConnectionAsEditor();
		
		$this->paginatorComponent = new PaginatorComponent(getUsersCount(($_GET["q"] ?? ""), $conn), 20);
		
		$this->dataRows = $this->transformDataRowsArray($conn);
		
		$this->dataGridComponent = new DataGridComponent($this->dataRows);
		$this->dataGridComponent->RudButtonsFunctionParamName = "ID";
		$this->dataGridComponent->selectButtonOnClick = "btnSelectUser_onClick(event, {param})";
		
		$conn->close();
	}
	
	function render()
	{
		
		$dgComp = $this->dataGridComponent;
		$pagComp = $this->paginatorComponent;
		
		$view = $this->get_view();		
		require_once($view);
	}
	
	private function transformDataRowsArray($conn)
	{
		$users = getUsersPartially($this->paginatorComponent->pageNum, 
												$this->paginatorComponent->numResultsOnPage,
												($_GET["orderBy"] ?? ""),
												($_GET["q"] ?? ""), $conn);
		$output = [];
		
		if ($users)
			foreach ($users as $u)
			{
				$row = [];
				$row["ID"] = $u["id"];
				$row["Nome"] = $u["name"];
				$row["E-mail"] = $u["email"];
				$row["Tipo"] = $u["typeName"];
				
				$output[] = $row;
			}
			
		return $output;
	}
}