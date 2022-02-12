<?php
require_once("model/database/widgets.database.php");

class LatestProfessorsWidget extends ComponentBase
{
	protected $name = "LatestProfessorsWidget";
	
	protected $moduleName = "PROFE";
	protected $permissionIdRequired = 1;
	
	public function __construct($optDBConnection = null)
	{
		parent::__construct();
		
		if ($this->hasUserPermission)
			$this->dataRows = getLatestProfessors($optDBConnection);
	}
	
	protected $dataRows;
	
	public function render()
	{
		$dataRows = $this->dataRows;
		
		$view = $this->get_view();
		require($view);
	}
}