<?php
namespace Professor;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/ProfessorDocInfos.php";
require_once __DIR__ . "/ProfessorWorkDocsContentGenerator.php";
require_once __DIR__ . "/ProfessorWorkDocsConditionChecker.php";
require_once __DIR__ . "/../common.php";

use tFPDF;

final class DocsPDF extends tFPDF\PDF
{
    private object $template;

    //private ProfessorDocInfos $professorInfos;
    private ProfessorWorkDocsContentGenerator $contentGenerator;
    private ProfessorWorkDocsConditionChecker $conditionChecker;

    public function __construct()
    {
        parent::__construct('P', 'mm', 'A4');
    }

    public function SetData(string $templateJson, ProfessorDocInfos $profDocInfos)
    {
        $this->template = json_decode($templateJson);
        $this->contentGenerator = new ProfessorWorkDocsContentGenerator($profDocInfos, $this);
        $this->conditionChecker = new ProfessorWorkDocsConditionChecker($profDocInfos);

        $this->AddFont("freesans", "", "FreeSans-LrmZ.ttf", true); 
		$this->AddFont("freesans", "B", "FreeSansBold-Xgdd.ttf", true);
		$this->AddFont("freesans", "I", "FreeSansOblique-ol30.ttf", true);
    }

    public function GenerateDocument()
    {
        $this->SetAutoPageBreak(false);
        foreach ($this->template->pages as $p)
        {
            if ($this->conditionChecker->CheckConditions($p->conditions ?? []))
                $this->drawPage($p);
        }
    }

    private function drawPage(object $pageDescriptor)
    {
        if (empty($pageDescriptor->margins))
            $this->SetMargins(30, 35, 20);
        else
            $this->SetMargins(...$pageDescriptor->margins);

		$this->AddPage();

        foreach ($pageDescriptor->elements as $el)
        {
            if (empty($el->conditions) || $this->conditionChecker->CheckConditions($el->conditions))
                $this->drawPageElement($el);
        }
    }

    private function drawPageElement(object $elementDescriptor)
    {
        $title = function ($elDesc)
        {
		    $this->SetFont('freesans','BU',20);
			$this->Cell(0, 10, $elDesc->content, 0, 1, $this->translateAlign($elDesc->align ?? ''));
            $this->ln();
        };

        $paragraph = function ($elDesc)
        {
            $finalContent = ($elDesc->useGeneratedContentTags ?? false) ?
                mb_ereg_replace_callback('\$\{(.+?)\}', function($matches)
                {
                    if (isset($this->contentGenerator->generatedContentTagsTable[$matches[1]]))
                        return $this->contentGenerator->generatedContentTagsTable[$matches[1]]();
                    else
                        return '***';
                }, $elDesc->content)
                :
                $elDesc->content;

            $this->SetFont('freesans','',12);
            $this->MultiCell(0, $elDesc->lineHeight ?? 7, $finalContent, 0, 'J');
            $this->Ln($elDesc->pBreakHeight ?? 9);
        };

        $sectionTitle = function ($elDesc) 
        {
            $this->SetFont('freesans','B',14);
            $this->Cell(0, 10, $elDesc->content, 0, 1, $this->translateAlign($elDesc->align ?? ''));
        };

        $orderedList = function($elDesc)
        {
            $this->SetFont('freesans','',12);

            foreach ($elDesc->items as $i => $item)
                $this->MultiCell(0, 8, ($i + 1) . ") " . $item, 0, $this->translateAlign($elDesc->align ?? ''));
        };

        $generatedContent = function ($elDesc) 
        {
            if (method_exists($this->contentGenerator, $elDesc->identifier))
                $this->contentGenerator->{$elDesc->identifier}($elDesc);
        };

        if (isset(${$elementDescriptor->type}))
            ${$elementDescriptor->type}($elementDescriptor);
    }

    private function translateAlign($descValue) : string
    {
        switch ($descValue)
        {
            case 'right': return 'R';
            case 'center': return 'C';
            case 'left':
            default: return 'L';
        }
    }
}