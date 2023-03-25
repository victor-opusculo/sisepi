<?php
require_once("model/Database/library.widgets.database.php");

class LibraryNextDevolutionsWidget extends ComponentBase
{
	protected $name = "LibraryNextDevolutionsWidget";
	
	protected $moduleName = "LIBR";
	protected $permissionIdRequired = 10;
	
	public function __construct($optDBConnection)
	{
		parent::__construct();
		
		if ($this->hasUserPermission)
		{
			$this->dataRows = getNextDevolutions($optDBConnection);
		}
		
	}
	
	private $dataRows;
	
	public function render()
	{
		$dataRows = $this->dataRows;
		
		$view = $this->get_view();
		require($view);
	}
}