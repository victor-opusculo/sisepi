<?php

namespace SisEpi\Model\Professors;

require_once __DIR__ . "/../../includes/common.php";
require_once __DIR__ . "/../../model/professors/Professor.php";
require_once __DIR__ . "/../../vendor/autoload.php";

use SisEpi\Model\Professors\Professor;
use Normalizer;
use tFPDF;

final class ProfResumePDF extends tFPDF
{
    public function __construct(Professor $prof)
    {
        parent::__construct('P', 'mm', 'A4');
        $this->professor = $prof;

        $this->AddFont("freesans", "", "FreeSans-LrmZ.ttf", true); 
		$this->AddFont("freesans", "B", "FreeSansBold-Xgdd.ttf", true);
		$this->AddFont("freesans", "I", "FreeSansOblique-ol30.ttf", true);

        $this->SetAutoPageBreak(true, 20);
    }

    private Professor $professor;

    private function mb_trim($str) 
    {
        return preg_replace("/^\s+|\s+$/u", "", $str); 
    }

    public function DrawPage()
    {
		$this->AddPage();
        $this->SetMargins(30, 30, 15);

		$this->SetFont('freesans','B',24);
        $this->SetX(30);
        $this->MultiCell(0, 10, Normalizer::normalize($this->professor->name, Normalizer::FORM_C), 0, 'C');
        
        $this->Ln(10);

		$this->SetFont('freesans','B', 14);
        $this->Cell(0, 15, "Formação Educacional/Acadêmica", 0, 1, 'C');
		$this->SetFont('freesans','', 12);
        $this->MultiCell(0, 7, Normalizer::normalize($this->professor->miniResumeJson->education ?? '', Normalizer::FORM_C));

        $this->SetFont('freesans','B', 14);
        $this->Cell(0, 15, "Experiência Profissional", 0, 1, 'C');
		$this->SetFont('freesans','', 12);
        $this->MultiCell(0, 7, Normalizer::normalize($this->professor->miniResumeJson->experience ?? '', Normalizer::FORM_C));

        $addInfo = $this->professor->miniResumeJson->additionalInformation ?? "";
        if (!empty($this->mb_trim($addInfo)))
        {
            $this->SetFont('freesans','B', 14);
            $this->Cell(0, 15, "Informações Complementares", 0, 1, 'C');
            $this->SetFont('freesans','', 12);
            $this->MultiCell(0, 7, Normalizer::normalize($addInfo, Normalizer::FORM_C));
        }
    }
}