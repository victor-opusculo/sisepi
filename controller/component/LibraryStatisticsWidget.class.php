<?php
require_once("model/Database/library.widgets.database.php");

class LibraryStatisticsWidget extends ComponentBase
{
	protected $name = "LibraryStatisticsWidget";
	
	//protected $moduleName = "EVENT";
	//protected $permissionIdRequired = 4;
	
	public function __construct($optDBConnection)
	{
		parent::__construct();
		
		//if ($this->hasUserPermission)
			$this->collectionCount = getCollectionCount($optDBConnection);
		$this->totalLoanCount = getTotalLoansCount($optDBConnection);
		$this->nonFinalizedLoanCount = getNonFinalizedLoansCount($optDBConnection);
		$this->totalReservationCount = getTotalReservationsCount($optDBConnection);
		$this->nonFinalizedReservationCount = getNonFinalizedReservationsCount($optDBConnection);
		$this->totalUsersCount = getTotalUsersCount($optDBConnection);
		
	}
	
	public $collectionCount;
	public $totalLoanCount, $nonFinalizedLoanCount;
	public $totalReservationCount, $nonFinalizedReservationCount;
	public $totalUsersCount;
	
	public function render()
	{
		$widgetObj = $this;
		
		$view = $this->get_view();
		require($view);
	}
}