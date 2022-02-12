<?php

class PaginatorComponent extends ComponentBase
{
	protected $name = "Paginator";
	
	public function __construct($totalItems, $numResultsOnPage)
	{
		parent::__construct();
		
		$this->pageNum = isset($_GET['pageNum']) && is_numeric($_GET['pageNum']) ? $_GET['pageNum'] : 1;
		$this->totalItems = $totalItems;
		$this->numResultsOnPage = $numResultsOnPage;
	}
	
	public $totalItems, $numResultsOnPage, $pageNum;
	
	public function render()
	{
		$totalItems = $this->totalItems;
		$numResultsOnPage = $this->numResultsOnPage;
		$pageNum = $this->pageNum;
		
		$view = $this->get_view();
		require_once($view);
	}
}