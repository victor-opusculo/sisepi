<?php
require_once("Database/artmuseum.database.php");

class ArtPiece
{
	/*public $id;
	public $name;
	public $artist;
	public $technique;
	public $year;
	public $size;
	public $donor;
	public $value;
	public $location;
	public $description;
	public $mainImageAttachmentFileName;*/
	
	public $attachments;
	
	public function __construct($id)
	{
		$this->attachments = [];
		
		if ($id === null) throw new Exception("Erro ao instanciar a classe ArtPiece. id nulo.");
		
		$this->buildObject($id);
	}
	
	private function buildObject($id)
	{
		$artPieceFullData = getFullArtPiece($id);
		
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