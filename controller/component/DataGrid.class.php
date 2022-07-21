<?php

class DataGridComponent extends ComponentBase
{
	protected $name = "DataGrid";
	
	public function __construct($dataRowsArray = null)
	{
		parent::__construct();
		
		$this->dataRows = $dataRowsArray;
	}
	
	public ?array $dataRows;
	
	public ?string $detailsButtonURL = null, $editButtonURL = null, $deleteButtonURL = null;
	public ?string $selectButtonOnClick = null;
	public ?string $columnNameAsDetailsButton = null;
	public ?string $RudButtonsFunctionParamName = "id"; 
	public array $columnsToHide = [];
	public array $customButtons = [];

	public array $customButtonsParameters = [];
	
	public function render()
	{
		$dataRows = $this->dataRows;
		$columnsToHide = $this->columnsToHide;
		
		$selectButtonOnClick = $this->selectButtonOnClick;
		$columnNameAsDetailsButton = $this->columnNameAsDetailsButton;
		$detailsButtonURL = $this->detailsButtonURL;
		$editButtonURL = $this->editButtonURL;
		$deleteButtonURL = $this->deleteButtonURL;

		$customButtons = $this->customButtons;
		
		$RudButtonsFunctionParamName = $this->RudButtonsFunctionParamName;
		$customButtonsParameters = $this->customButtonsParameters;
		
		$view = $this->get_view();
		require($view);
	}
}

class DataGridIcon
{
	private string $file;
	private string $altText;
	private string $title;
	public $textAfterIcon = "";
	
	public function __construct(string $filePathFromSystemDir, string $altText, string $title = null)
	{
		$this->file = $filePathFromSystemDir;
		$this->altText = $altText;
		$this->title = $title ?? $altText;
	}
	
	public function generateHTML() : string
	{
		return '<img style="vertical-align: middle;" src="' . URL\URLGenerator::generateFileURL($this->file) . '" alt="' . $this->altText . '" title="' . $this->title . '"/> ' . $this->textAfterIcon;
	}
}

class FixedParameter
{
	private $value;

	public function __construct($parameterValue)
	{
		$this->value = $parameterValue;
	}

	public function __toString() : string
	{
		return $this->value;
	}
}