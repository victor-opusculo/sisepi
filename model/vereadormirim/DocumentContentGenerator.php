<?php
namespace SisEpi\Model\VereadorMirim;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/DocumentInfos.php';

use tFPDF;
use Data;

define('IMG_SISEPI_LOGO_FILE', __DIR__ .  '/../../pics/sisepilogo.png');

final class DocumentContentGenerator
{
    private DocumentInfos $docInfos;
    private tFPDF $pdf;
    public array $generatedContentTagsTable;

    public function __construct(DocumentInfos $docInfos, tFPDF $pdf)
    {
        $this->docInfos = $docInfos;
        $this->pdf = $pdf;
        $this->generatedContentTagsTable = $this->getGeneratedContentTagsTable();
    }

    public function cityAndDate(object $elDesc)
    {
        $formatEndDate = function($dateTime)
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
        };

        $finalText = 'Itapevi, ' . $formatEndDate(new \DateTime($this->docInfos->document->signatureDate));

        $pdf = $this->pdf;
        $pdf->Ln();
        $pdf->SetFont('freesans','',12);
        $pdf->Cell(0, 8, $finalText, 0, 1, $this->translateAlign($elDesc->align ?? ''));
    }

    public function vmParentSignatureField(object $elDesc)
    {
        $this->signatureField($elDesc, 'parent');
    }

    public function vmStudentSignatureField(object $elDesc)
    {
        $this->signatureField($elDesc, 'student');
    }

    public function vmSchoolSignatureField(object $elDesc)
    {
        $this->signatureField($elDesc, 'school');
    }

    private function signatureField(object $elDesc, string $signerType)
    {
        $pdf = $this->pdf;

        $signerPtBrType = $signerType === 'parent' ? "pai/responsável" : ($signerType === 'student' ? "estudante" : "escola");
        
        $originalX = $pdf->GetX();
        $originalY = $pdf->GetY();

        if (!empty($this->docInfos->document->signatures))
            foreach ($this->docInfos->document->signatures as $signature) 
                if ($signature->docSignatureId === (int)$elDesc->docSignatureId)
                {
                    $elDesc->align = $elDesc->align ?? 'left';
                    $signX = $elDesc->align === "left" ? $pdf->GetX() : ($elDesc->align === "right" ? $pdf->GetPageWidth() - 120 : ($pdf->GetPageWidth() / 2) - 50);
                    $pdf->Image(IMG_SISEPI_LOGO_FILE, $signX, $pdf->GetY(), 20, 0);
                    $pdf->SetX($signX + 20);
                    $code = $signature->id;
                    $signatureDateTime = date_create($signature->signatureDateTime);
                    $signatureDateTimeStr = $signatureDateTime->format('d/m/Y H:i:s');
                    $authText = "Aceito eletronicamente por $signerPtBrType via sistema da Escola do Parlamento. Verifique a autenticidade desta assinatura em: " . \AUTH_ADDRESS . 
                    " e informe os dados: Código $code - Data de assinatura: $signatureDateTimeStr "; 
                    $pdf->SetFont('freesans','',8);

                    $queryString = 'code=' . $code . '&date=' . $signatureDateTime->format('Y-m-d') . "&time=" . $signatureDateTime->format('H:i:s');
                    $fullLink = is_integer(mb_strpos(AUTH_ADDRESS, '?')) ? AUTH_ADDRESS . '&' . $queryString : AUTH_ADDRESS . '?' . $queryString;
                    $pdf->Link($signX, $pdf->GetY(), 110, 25, $fullLink);
                    $pdf->MultiCell(90, 4, $authText, 0, 'J',);
                    break;
                }
        $pdf->SetXY($originalX, $originalY);
        $pdf->Ln(22);
        $pdf->SetFont('freesans','I',12);
        if ($signerType === 'parent')
            $pdf->Cell(0, 8, $this->docInfos->document->documentData->parent->name, 0, 1, $this->translateAlign($elDesc->align ?? ''));
        else if ($signerType === 'student')
            $pdf->Cell(0, 8, $this->docInfos->document->documentData->student->name, 0, 1, $this->translateAlign($elDesc->align ?? ''));
        //else if ($signerType === 'school')

        $pdf->Ln();
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

    private function getGeneratedContentTagsTable() : array
    {
        $parent = $this->docInfos->document->documentData->parent;
        $student = $this->docInfos->document->documentData->student;
        $document = $this->docInfos->document;

        return
        [
            'vmStudentName' => fn() => $student->name,
            'vmStudentRg' => fn() => $student->studentDataJson->rg,
            'vmStudentRgIssuingAgency' => fn() => $student->studentDataJson->rgIssuingAgency,
            'vmParentName' => fn() => $parent->name ?? '***',
            'vmParentRg' => fn() => $parent->parentDataJson->rg ?? '***',
            'vmParentRgIssuingAgency' => fn() => $parent->parentDataJson->rgIssuingAgency ?? '***',
            'vmStudentAddressStreet' => fn() => $student->studentDataJson->homeAddress->street,
            'vmStudentAddressNumber' => fn() => $student->studentDataJson->homeAddress->number,
            'vmStudentAddressCity' => fn() => $student->studentDataJson->homeAddress->city,
            'vmStudentAddressUf' => fn() => $student->studentDataJson->homeAddress->stateUf,
            'vmStudentAccessibilityRequired' => fn() => $student->studentDataJson->accessibilityRequired ?? '***',
            'vmParentPhone' => fn() => !empty($parent->parentDataJson->phones->cellphone) ? 
                $parent->parentDataJson->phones->cellphone :
                ($parent->parentDataJson->phones->landline ?? '***')
        ];
    } 
}
