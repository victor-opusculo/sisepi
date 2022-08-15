<?php
require_once("model/database/widgets.database.php");

class PendingProfWorkProposalsWidget extends ComponentBase
{
	protected $name = "PendingProfWorkProposalsWidget";
	
	protected $moduleName = "PROFE";
	protected $permissionIdRequired = 5;
	
	public function __construct($optDBConnection = null)
	{
		parent::__construct();
		
		if ($this->hasUserPermission)
			$this->dataRows = getPendingProfessorWorkProposals($optDBConnection);
	}
	
	protected $dataRows;
	
	public function render()
	{
		$dataRows = $this->dataRows;
		
		$view = $this->get_view();
		require($view);
	}
}