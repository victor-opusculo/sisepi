<?php
require_once("model/Database/widgets.database.php");

class NextChecklistsWidget extends ComponentBase
{
	protected $name = "NextChecklistsWidget";
	
	protected $moduleName = "CHKLS";
	protected $permissionIdRequired = 3;
	
	public function __construct($optDBConnection = null)
	{
		parent::__construct();
		
		if ($this->hasUserPermission)
			$this->nextChecklistsDataRows = getNextChecklists($_SESSION['userid'], $optDBConnection);
	}
	
	protected $nextChecklistsDataRows;
	
	public function render()
	{
		$nextChecklistsDataRows = $this->nextChecklistsDataRows;
		
		$view = $this->get_view();
		require($view);
	}
}