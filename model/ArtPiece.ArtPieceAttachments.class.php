<?php
require_once("Database/artmuseum.database.php");

class ArtPiece
{
	public $id;
	public $CMI_propertyNumber;
	public $name;
	public $artist;
	public $technique;
	public $year;
	public $size;
	public $donor;
	public $value;
	public $location;
	public $description;
	public $mainImageAttachmentFileName;
	
	public $attachments;
	
	public function __construct($id, $optConnection = null)
	{
		$this->attachments = [];
		
		if ($id === null) throw new Exception("Erro ao instanciar a classe ArtPiece. id nulo.");
		
		if ($id === "new")
			$this->buildEmptyObject();
		else
			$this->buildObject($id, $optConnection);
	}
	
	private function buildEmptyObject()
	{
		$this->id = "";
		$this->CMI_propertyNumber = "";
		$this->name = "";
		$this->artist = "";
		$this->technique = "";
		$this->year = "";
		$this->size = "";
		$this->donor = "";
		$this->value = "0";
		$this->location = "";
		$this->description = "";
		$this->mainImageAttachmentFileName = null;
	}
	
	private function buildObject($id, $optConnection = null)
	{
		$artPieceFullData = getFullArtPiece($id, $optConnection);
		
		if ($artPieceFullData["artPiece"] === null) throw new Exception("ID de obra de arte não localizado.");
		
		foreach ($artPieceFullData["artPiece"] as $column => $value)
			$this->$column = $value;
			
		if ($artPieceFullData["artPieceAttachments"])
			foreach ($artPieceFullData["artPieceAttachments"] as $dataRow)
				$this->attachments[] = new ArtPieceAttachment($dataRow);
		
	}
}

class ArtPieceAttachment
{
	public function __construct($dataRow)
	{
		foreach ($dataRow as $column => $value)
			$this->$column = $value;
	}
}