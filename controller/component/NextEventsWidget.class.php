<?php
require_once("model/database/widgets.database.php");

class NextEventsWidget extends ComponentBase
{
	protected $name = "NextEventsWidget";
	
	protected $moduleName = "EVENT";
	protected $permissionIdRequired = 4;
	
	public function __construct($optDBConnection = null)
	{
		parent::__construct();
		
		if ($this->hasUserPermission)
			$this->dataRows = getNextEvents($optDBConnection);
	}
	
	protected $dataRows;
	
	public function render()
	{
		$dataRows = $this->dataRows;
		
		$view = $this->get_view();
		require($view);
	}
}