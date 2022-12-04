<?php

class ToggleButtonComponent extends ComponentBase
{
	protected $name = "ToggleButton";
	
	public function __construct($buttonContent)
	{
		parent::__construct();
		
		$this->buttonContent = $buttonContent;
	}
	
	public $buttonContent;
	
	public function render()
	{
		$buttonContent = $this->buttonContent;
		
		$view = $this->get_view();
		require $view;
	}
}