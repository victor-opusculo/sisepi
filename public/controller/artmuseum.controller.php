<?php
require_once("model/database/artmuseum.database.php");

final class artmuseum extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Museu de Arte";
		$this->subtitle = "Museu de Arte";
	}
	
	public function home()
	{
		require_once("controller/component/Gallery.class.php");
		require_once("controller/component/Paginator.class.php");
		
		$conn = createConnectionAsEditor();
		
		$piecesCount = getArtPiecesCount($_GET["q"] ?? "", $conn);
		
		$paginatorComponent = new PaginatorComponent($piecesCount, 9);
		
		$dataRows = getArtPiecesPartially($paginatorComponent->pageNum,
												$paginatorComponent->numResultsOnPage,
												($_GET["orderBy"] ?? ""),
												($_GET["q"] ?? ""),
												$conn);
		
		$conn->close();
		
		$galleryComponent = new GalleryComponent($dataRows);
		$galleryComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("artmuseum", "view", "{param}");
		
		$galleryComponent->framesTitleGeneratorFunction = function($dataRow) { return $dataRow["name"]; };
		$galleryComponent->framesOtherInfosGeneratorFunctions[] = function($dataRow) { return "Artista: " . $dataRow["artist"]; };
		$galleryComponent->framesOtherInfosGeneratorFunctions[] = function($dataRow) { return "Técnica: " . $dataRow["technique"]; };
		$galleryComponent->framesYearGeneratorFunction = function($dataRow) { return $dataRow["year"]; };
		$galleryComponent->framesImageGeneratorFunction = function($dataRow) 
		{ 
			if ($dataRow["mainImageAttachmentFileName"] !== null)
				return URL\URLGenerator::generateBaseDirFileURL("uploads/art/" . $dataRow["id"] . "/" . $dataRow["mainImageAttachmentFileName"]);
			else
				return null;
		};
		
		$this->view_PageData['piecesCount'] = $piecesCount;
		$this->view_PageData['galComp'] = $galleryComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
	}
	
	public function pre_view()
	{
		$this->title = "SisEPI - Ver obra de arte";
		$this->subtitle = "Ver obra de arte";
	}
	
	public function view()
	{
		require_once("model/ArtPiece.ArtPieceAttachments.class.php");
		
		$artPieceId = (isset($_GET["id"]) && is_numeric($_GET["id"])) ? $_GET["id"] : 0;
		$artPieceObject = null;
		try
		{
			$artPieceObject = new ArtPiece($artPieceId);
		}
		catch (Exception $e)
		{
			$artPieceObject = null;
			$this->pageMessages[] = "Erro ao abrir obra de arte.";
			$this->pageMessages[] = $e->getMessage();
		}
		
		$this->view_PageData['artPieceObj'] = $artPieceObject;
	}
}