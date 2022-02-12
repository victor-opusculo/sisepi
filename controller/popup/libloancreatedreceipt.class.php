<?php

require_once("model/database/libraryborrowedpubs.database.php");
require_once("model/GenericObjectFromDataRow.class.php");

class libloancreatedreceiptClass extends PopupBasePage
{
	protected $title = "SisEPI - Biblioteca: Comprovante de devolução";
	
	private $borrowedPubId;
	private $borrowedPubObject;
	
	private $pageMessages = [];
	
	protected function postConstruct()
	{
		$conn = createConnectionAsEditor();
		
		$this->borrowedPubId = isset($_GET["id"]) && isId($_GET["id"]) ? $_GET["id"] : 0;
		try
		{
			$this->borrowedPubObject = new GenericObjectFromDataRow(getSingleBorrowedPub($this->borrowedPubId, $conn));
		}
		catch (Exception $e)
		{
			$this->borrowedPubObject = null;
			$this->pageMessages[] = $e->getMessage();
		}
		
		$conn->close();
	}
	
	function render()
	{
		$pageMessages = $this->pageMessages;
		
		$bpubObj = $this->borrowedPubObject;
		
		$view = $this->get_view();		
		require_once($view);
	}
}