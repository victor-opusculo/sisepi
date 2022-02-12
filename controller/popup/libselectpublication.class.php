<?php
require_once("model/database/librarycollection.database.php");
require_once("controller/component/DataGrid.class.php");
require_once("controller/component/Paginator.class.php");

class libselectpublicationClass extends PopupBasePage
{
	protected $title = "SisEPI - Biblioteca: Selecionar publicação";
	
	protected $moduleName = "LIBR";
	protected $permissionIdRequired = 2;
	
	private $dataRows;
	private $dataGridComponent;
	private $paginatorComponent;
	
	private $collectionTypesList;
		
	protected function postConstruct()
	{
		$conn = createConnectionAsEditor();
		
		$this->paginatorComponent = new PaginatorComponent(getCollectionCount(($_GET["colTypeId"] ?? ""), ($_GET["q"] ?? ""), $conn), 20);
		
		$this->dataRows = $this->transformDataRowsArray($conn);
		
		$this->collectionTypesList = getCollectionTypes($conn);
		
		$this->dataGridComponent = new DataGridComponent($this->dataRows);
		$this->dataGridComponent->RudButtonsFunctionParamName = "ID";
		$this->dataGridComponent->selectButtonOnClick = "btnSelectPublication_onClick(event, {param})";
		
		$conn->close();
	}
	
	function render()
	{
		$collectionTypesList = $this->collectionTypesList;
		
		$dgComp = $this->dataGridComponent;
		$pagComp = $this->paginatorComponent;
		
		$view = $this->get_view();		
		require_once($view);
	}
	
	private function transformDataRowsArray($conn)
	{
		$collection = getCollectionPartially($this->paginatorComponent->pageNum, 
												$this->paginatorComponent->numResultsOnPage,
												($_GET["orderBy"] ?? ""),
												($_GET["colTypeId"] ?? ""),
												($_GET["q"] ?? ""), $conn);
		$output = [];
		
		if ($collection)
			foreach ($collection as $pub)
			{
				$row = [];
				$row["ID"] = $pub["id"];
				$row["Cat. Acervo"] = $pub["collectionTypeName"];
				$row["Título"] = $pub["title"];
				$row["Autor"] = $pub["author"];
				$row["CDU/CDD/ISBN"] = $this->formatCDU_CDD_ISBN($pub);
				if (checkForExtraColumnFlag(1)) $row["Edição"] = $pub["edition"];
				if (checkForExtraColumnFlag(2)) $row["Volume"] = $pub["volume"];
				if (checkForExtraColumnFlag(4)) $row["Exemplar"] = $pub["copyNumber"];
				
				$output[] = $row;
			}
			
		return $output;
	}
	
	private function formatCDU_CDD_ISBN($pubDataRow)
	{
		$output = "";
		if ($pubDataRow["cdu"])
		{
			$output .= "CDU: " . $pubDataRow["cdu"];
			$output .= $pubDataRow["cdd"] || $pubDataRow["isbn"] ? PHP_EOL : "";
		}
		
		if ($pubDataRow["cdd"])
		{
			$output .= "CDD: " . $pubDataRow["cdd"];
			$output .= $pubDataRow["isbn"] ? PHP_EOL : "";
		}
		
		if ($pubDataRow["isbn"])
		{
			$output .= "ISBN: " . $pubDataRow["isbn"];
		}
		
		return $output;
	}
}

function checkForExtraColumnFlag($flagValue)
{
	if (isset($_GET["extraColumns"]) && is_numeric($_GET["extraColumns"]))
		return (int)$_GET["extraColumns"] & $flagValue;
	else
		return false;
}