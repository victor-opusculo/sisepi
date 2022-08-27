<?php

namespace Professor;

//require_once(__DIR__ ."/../tfpdf/tfpdf.php");
require_once __DIR__ . "/../../vendor/autoload.php";


define('BASEDIR_CERTIFICATES_RESOURCES', __DIR__ . '/../../generate/certificates');

class ProfessorCertificatePDF extends \tFPDF\PDF
{
	private $professor;
	private $workSheet;
	private $authInfos;
	
    public function __construct($professor, $workSheet, $authInfos)
    {
        parent::__construct('L', 'mm', 'A4');

        $this->professor = $professor;
		$this->workSheet = $workSheet;
		$this->authInfos = $authInfos;

		$this->AddFont("freesans", "", "FreeSans-LrmZ.ttf", true); 
		$this->AddFont("freesans", "B", "FreeSansBold-Xgdd.ttf", true);
		$this->AddFont("freesans", "I", "FreeSansOblique-ol30.ttf", true);
    }
	
	public function DrawFrontPage()
	{
		$this->AddPage();

		$this->Image(BASEDIR_CERTIFICATES_RESOURCES . "/" . $this->workSheet->certificateBgFile, 0, 0, 297, 210, "JPG"); //Face page, background image
		$this->Image(BASEDIR_CERTIFICATES_RESOURCES . "/cmilogo.png", 5, 186, 40, null, "PNG"); //CMI logo image

		$this->setY(75);
		
		$this->setX(42.5);
		$this->SetFont('freesans','B',24);
		$this->MultiCell(212,13, $this->professor->name, 0, "C"); //Professor name
		
		$this->SetFont('freesans','',13);
		$this->setX(42.5);
		$this->MultiCell(212, 6, $this->workSheet->professorCertificateText, 0, "C"); //Main text

        $this->Ln();
		$this->Cell(0, 11, "Itapevi, " . $this->formatEndDate(date_create($this->workSheet->signatureDate)), 0, 2, "C");
	}
	
	public function DrawBackPage()
	{
		$this->AddPage(); //Back
		$this->Image(BASEDIR_CERTIFICATES_RESOURCES . "/certbackbottom.png", 0, 160, 297, 0, "PNG"); //Back logos
		$this->drawAuthenticationInfo();
	}
	
	private function formatEndDate($dateTime)
	{
		$monthNumber = (int)$dateTime->format("m");
		$monthName = "";
		switch ($monthNumber)
		{
			case 1: $monthName = "janeiro"; break;
			case 2: $monthName = "fevereiro"; break;
			case 3: $monthName = "março"; break;
			case 4: $monthName = "abril"; break;
			case 5: $monthName = "maio"; break;
			case 6: $monthName = "junho"; break;
			case 7: $monthName = "julho"; break;
			case 8: $monthName = "agosto"; break;
			case 9: $monthName = "setembro"; break;
			case 10: $monthName = "outubro"; break;
			case 11: $monthName = "novembro"; break;
			case 12: $monthName = "dezembro"; break;
		}
		
		$dayNumber = (int)$dateTime->format("j") === 1 ? ("1º") : $dateTime->format("j");
		
		return $dayNumber . " de " . ($monthName) . " de " . $dateTime->format("Y");
	}
	
	private function drawAuthenticationInfo()
	{
		$this->SetX(150);
		$this->SetY($this->GetPageHeight() - 45);
		$this->SetFont("freesans", "I", 11);
		
		$code = $this->authInfos["code"];
		$issueDateTime = date_format(date_create($this->authInfos["issueDateTime"]), "d/m/Y H:i:s");
				
		$authText = "Verifique a autenticidade deste certificado em: " . AUTH_ADDRESS . " e informe os seguintes dados: Código $code - Emissão inicial em $issueDateTime.";
		$this->MultiCell(200, 5, $authText, 0, "L");
	}
}