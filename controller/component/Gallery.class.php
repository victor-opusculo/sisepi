<?php

class GalleryComponent extends ComponentBase
{
	protected $name = "Gallery";
	
	public function __construct($dataRowsArray)
	{
		parent::__construct();
		
		$this->dataRows = $dataRowsArray;
	}
	
	protected $dataRows;
	
	public $detailsButtonURL, $editButtonURL, $deleteButtonURL = null;
	public $RudButtonsFunctionParamName = "id"; 
	
	public $framesTitleGeneratorFunction;
	public $framesOtherInfosGeneratorFunctions = [];
	public $framesYearGeneratorFunction;
	public $framesImageGeneratorFunction;
	
	public function render()
	{
		$dataRows = $this->dataRows;
		
		$detailsButtonURL = $this->detailsButtonURL;
		$editButtonURL = $this->editButtonURL;
		$deleteButtonURL = $this->deleteButtonURL;
		
		$RudButtonsFunctionParamName = $this->RudButtonsFunctionParamName;
		
		$framesTitleGeneratorFunction = $this->framesTitleGeneratorFunction;
		$framesOtherInfosGeneratorFunctions = $this->framesOtherInfosGeneratorFunctions;
		$framesYearGeneratorFunction = $this->framesYearGeneratorFunction;
		$framesImageGeneratorFunction = $this->framesImageGeneratorFunction;
		
		$view = $this->get_view();
		require_once($view);
	}
}