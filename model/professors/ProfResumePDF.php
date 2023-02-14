<?php

namespace Model\Professors;

require_once "../includes/common.php";
require_once "../model/professors/Professor.php";
require_once "../vendor/autoload.php";

use \Model\Professors\Professor;
use tFPDF;

final class ProfResumePDF extends tFPDF\PDF
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
        $this->MultiCell(0, 10, $this->professor->name, 0, 'C');
        
        $this->Ln(10);

		$this->SetFont('freesans','B', 14);
        $this->Cell(0, 15, "Formação Educacional/Acadêmica", 0, 1, 'C');
		$this->SetFont('freesans','', 12);
        $this->MultiCell(0, 7, $this->professor->miniResumeJson->education ?? "");

        $this->SetFont('freesans','B', 14);
        $this->Cell(0, 15, "Experiência Profissional", 0, 1, 'C');
		$this->SetFont('freesans','', 12);
        $this->MultiCell(0, 7, $this->professor->miniResumeJson->experience ?? "");

        $addInfo = $this->professor->miniResumeJson->additionalInformation ?? "";
        if (!empty($this->mb_trim($addInfo)))
        {
            $this->SetFont('freesans','B', 14);
            $this->Cell(0, 15, "Informações Complementares", 0, 1, 'C');
            $this->SetFont('freesans','', 12);
            $this->MultiCell(0, 7, $addInfo);
        }
    }
}