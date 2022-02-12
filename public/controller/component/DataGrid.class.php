<?php

class DataGridComponent extends ComponentBase
{
	protected $name = "DataGrid";
	
	public function __construct($dataRowsArray = null)
	{
		parent::__construct();
		
		$this->dataRows = $dataRowsArray;
	}
	
	public $dataRows;
	
	public $detailsButtonURL, $editButtonURL, $deleteButtonURL = null;
	public $selectButtonOnClick = null;
	public $columnNameAsDetailsButton;
	public $RudButtonsFunctionParamName = "id"; 
	public $columnsToHide = [];
	
	public function render()
	{
		$dataRows = $this->dataRows;
		$columnsToHide = $this->columnsToHide;
		
		$selectButtonOnClick = $this->selectButtonOnClick;
		$columnNameAsDetailsButton = $this->columnNameAsDetailsButton;
		$detailsButtonURL = $this->detailsButtonURL;
		$editButtonURL = $this->editButtonURL;
		$deleteButtonURL = $this->deleteButtonURL;
		
		$RudButtonsFunctionParamName = $this->RudButtonsFunctionParamName;
		
		$view = $this->get_view();
		require($view);
	}
}

class DataGridIcon
{
	private $file;
	private $altText;
	private $title;
	public $textAfterIcon = "";
	
	public function __construct($filePathFromSystemDir, $altText, $title = null)
	{
		$this->file = $filePathFromSystemDir;
		$this->altText = $altText;
		$this->title = $title ?? $altText;
	}
	
	public function generateHTML()
	{
		return '<img src="' . URL\URLGenerator::generateFileURL($this->file) . '" alt="' . $this->altText . '" title="' . $this->title . '"/>' . $this->textAfterIcon;
	}
}