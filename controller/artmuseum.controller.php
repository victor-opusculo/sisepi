<?php
require_once("model/Database/artmuseum.database.php");
require_once("model/ArtPiece.ArtPieceAttachments.class.php");

final class artmuseum extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Museu de Arte";
		$this->subtitle = "Museu de Arte";
		
		$this->moduleName = "ARTM";
		$this->permissionIdRequired = 1;
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
		
		$totalValue = getArtPiecesValueSum(($_GET["q"] ?? ""), $conn);
		
		$conn->close();
		
		$galleryComponent = new GalleryComponent($dataRows);
		$galleryComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("artmuseum", "view", "{param}");
		$galleryComponent->editButtonURL = URL\URLGenerator::generateSystemURL("artmuseum", "edit", "{param}");
		$galleryComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("artmuseum", "delete", "{param}");
		
		$galleryComponent->framesTitleGeneratorFunction = function($dataRow) { return $dataRow["name"]; };
		$galleryComponent->framesOtherInfosGeneratorFunctions[] = function($dataRow) { return "Artista: " . $dataRow["artist"]; };
		$galleryComponent->framesOtherInfosGeneratorFunctions[] = function($dataRow) { return "Técnica: " . $dataRow["technique"]; };
		$galleryComponent->framesYearGeneratorFunction = function($dataRow) { return $dataRow["year"]; };
		$galleryComponent->framesImageGeneratorFunction = function($dataRow) 
		{ 
			if ($dataRow["mainImageAttachmentFileName"] !== null)
				return URL\URLGenerator::generateFileURL("uploads/art/" . $dataRow["id"] . "/" . $dataRow["mainImageAttachmentFileName"]); 
			else
				return null;
		};
		
		$this->view_PageData['totalValue'] = $totalValue;
		$this->view_PageData['piecesCount'] = $piecesCount;
		$this->view_PageData['galComp'] = $galleryComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
	}
	
	public function pre_view()
	{
		$this->title = "SisEPI - Ver obra de arte";
		$this->subtitle = "Ver obra de arte";
		
		$this->moduleName = "ARTM";
		$this->permissionIdRequired = 1;
	}
	
	public function view()
	{
		$artPieceId = (isset($_GET["id"]) && is_numeric($_GET["id"])) ? $_GET["id"] : 0;
		$artPieceCMI_propNumber = (isset($_GET["cmiPropNumber"]) && is_numeric($_GET["cmiPropNumber"])) ? $_GET["cmiPropNumber"] : null;

		$artPieceObject = null;
		$conn = createConnectionAsEditor();
		try
		{
			$artPieceObject = $artPieceCMI_propNumber ? 
									new ArtPiece(getSingleArtPieceIdFromPropertyNumber($artPieceCMI_propNumber, $conn), $conn) : 
									new ArtPiece($artPieceId, $conn);
		}
		catch (Exception $e)
		{
			$artPieceObject = null;
			$this->pageMessages[] = "Erro ao abrir obra de arte.";
			$this->pageMessages[] = $e->getMessage();
		}
		finally
		{
			$conn->close();
		}
		
		$this->view_PageData['artPieceObj'] = $artPieceObject;
	}
	
	public function pre_create()
	{
		$this->title = "SisEPI - Criar obra de arte";
		$this->subtitle = "Criar obra de arte";
		
		$this->moduleName = "ARTM";
		$this->permissionIdRequired = 2;
	}
	
	public function create()
	{
		if (empty($_GET["messages"]))
		{
			$this->action = "edit";
		
			$artPieceObject = new ArtPiece("new");
			
			$this->view_PageData['operation'] = "create";
			$this->view_PageData['artPieceObj'] = $artPieceObject;
		}
		else
			$this->action = "create_sent";
		
	}
		
	public function pre_edit()
	{
		$this->title = "SisEPI - Editar obra de arte";
		$this->subtitle = "Editar obra de arte";
		
		$this->moduleName = "ARTM";
		$this->permissionIdRequired = 3;
	}
	
	public function edit()
	{
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
		
		$this->view_PageData['operation'] = "edit";
		$this->view_PageData['artPieceObj'] = $artPieceObject;
	}
	
	public function pre_delete()
	{
		$this->title = "SisEPI - Excluir obra de arte";
		$this->subtitle = "Excluir obra de arte";
		
		$this->moduleName = "ARTM";
		$this->permissionIdRequired = 4;
	}
	
	public function delete()
	{
		$artPieceId = (isset($_GET["id"]) && is_numeric($_GET["id"])) ? $_GET["id"] : 0;

		if (!isset($_GET["messages"]))
		{
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
}