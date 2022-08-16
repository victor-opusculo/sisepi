<?php
namespace Professor;

require_once __DIR__ . '/ProfessorDocInfos.php';
require_once __DIR__ . '/../tfpdf/tfpdf.php';

use tFPDF;
use Data;

define('IMG_SISEPI_LOGO_FILE', __DIR__ . '/../../pics/sisepilogo.png');

final class ProfessorWorkDocsContentGenerator
{
    private ProfessorDocInfos $professorInfos;
    private tFPDF $pdf;
    public array $generatedContentTagsTable;

    public function __construct(ProfessorDocInfos $profInfos, tFPDF $pdf)
    {
        $this->professorInfos = $profInfos;
        $this->pdf = $pdf;
        $this->generatedContentTagsTable = $this->getGeneratedContentTagsTable();
    }

    public function professorPersonalData1()
    {
        $pdf = $this->pdf;
        $pdf->SetFont('freesans','B',12);

        $tableHeadersWidths = [50, 110];
        $rowHeight = 8;
        $pdf->SetFillColor(181, 230, 29);

        $pdf->Cell(array_sum($tableHeadersWidths), $rowHeight, 'Dados pessoais', 'TLR', 1, 'C', true);

        $pdf->SetFont('freesans','',12);
        //Nome:
        $row1x = $pdf->GetX(); 
        $row1y = $pdf->GetY();
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'Nome:', 0, 0, 'C');
        $pdf->MultiCell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->professor->name, 0, 'L');
        $pdf->Rect($row1x, $row1y, array_sum($tableHeadersWidths), $pdf->GetY() - $row1y);

        //RG:
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'RG nº:', 'LB', 0, 'C');
        $pdf->Cell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->professor->personalDocsJson->rg . ' ' . $this->professorInfos->professor->personalDocsJson->rgIssuingAgency, 'RB', 1, 'L');

        //CPF:
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'CPF nº:', 'LB', 0, 'C');
        $pdf->Cell($tableHeadersWidths[1], $rowHeight, Data\formatCPF($this->professorInfos->professor->personalDocsJson->cpf), 'RB', 1, 'L');

        //Qualificação:
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'Qualificação:', 'L', 0, 'C');
        $pdf->Cell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->workSheet->paymentInfosJson->paymentLevelTables[$this->professorInfos->workSheet->paymentTableId]->levels[$this->professorInfos->workSheet->paymentLevelId]->name, 'R', 1, 'L');
        
        //Endereço:
        $row5x = $pdf->GetX(); 
        $row5y = $pdf->GetY();
        $addObj = $this->professorInfos->professor->homeAddressJson;
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'Endereço:', 0, 0, 'C');
        $pdf->MultiCell($tableHeadersWidths[1], $rowHeight, 
            "$addObj->street, $addObj->number $addObj->complement - $addObj->neighborhood - $addObj->city/$addObj->state" , 0, 'L');
        $pdf->Rect($row5x, $row5y, array_sum($tableHeadersWidths), $pdf->GetY() - $row5y);

        //PIS/PASEP:
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'PIS/PASEP:', 'LB', 0, 'C');
        $pdf->Cell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->professor->personalDocsJson->pis_pasep, 'RB', 1, 'L');

        //Recolhe INSS:
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'Recolhe INSS:', 'LB', 0, 'C');
        $pdf->Cell($tableHeadersWidths[1], $rowHeight, (bool)$this->professorInfos->workSheet->paymentInfosJson->collectInss ? 'SIM' : 'NÃO', 'RB', 1, 'L');
        
        $pdf->Ln();
    }

    public function professorPersonalData2()
    {
        $pdf = $this->pdf;
        $pdf->SetFont('freesans','B',12);

        $tableHeadersWidths = [50, 110];
        $rowHeight = 8;
        $pdf->SetFillColor(181, 230, 29);

        $pdf->Cell(array_sum($tableHeadersWidths), $rowHeight, 'Contatos', 1, 1, 'C', true);

        $pdf->SetFont('freesans','',12);
        //E-mail:
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'E-mail:', 'LB', 0, 'C');
        $pdf->Cell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->professor->email, 'RB', 1, 'L');

        //Telefone:
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'Telefone:', 'LB', 0, 'C');
        $pdf->Cell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->professor->telephone, 'RB', 1, 'L');

        $pdf->SetFont('freesans','B',12);
        $pdf->Cell(array_sum($tableHeadersWidths), $rowHeight, 'Dados bancários', 1, 1, 'C', true);
        $pdf->SetFont('freesans','',12);

        //Banco:
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'Banco:', 'LB', 0, 'C');
        $pdf->Cell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->professor->bankDataJson->bankName, 'RB', 1, 'L');

        //Agência:
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'Agência:', 'LB', 0, 'C');
        $pdf->Cell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->professor->bankDataJson->agency, 'RB', 1, 'L');

        //Conta:
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'Nº da conta:', 'LB', 0, 'C');
        $pdf->Cell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->professor->bankDataJson->account, 'RB', 1, 'L');

        //PIX:
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'Chave Pix:', 'LB', 0, 'C');
        $pdf->Cell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->professor->bankDataJson->pix, 'RB', 1, 'L');

        $pdf->Ln();
    }

    public function workProposalType()
    {
        $pdf = $this->pdf;
        $pdf->SetFont('freesans','',12);

        foreach ($this->professorInfos->workSheet->paymentInfosJson->professorTypes as $i => $pType)
            $pdf->Cell(0, 8, ($i + 1) . ") (" . ((int)$this->professorInfos->workSheet->professorTypeId === $i ? 'x' : '  ') . ") " . $pType->name, 0, 1, 'L');
   
        $pdf->Ln();
    }

    public function professorActivityInformation()
    {
        $pdf = $this->pdf;
        $pdf->SetFont('freesans','',12);

        $tableHeadersWidths = [50, 110];
        $rowHeight = 8;

        //Atividade:
        $rowx = $pdf->GetX(); 
        $rowy = $pdf->GetY();
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'Título:', 0, 0, 'C');
        $pdf->MultiCell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->workSheet->participationEventDataJson->activityName, 0, 'L');
        $pdf->Rect($rowx, $rowy, array_sum($tableHeadersWidths), $pdf->GetY() - $rowy);

        //Data:
        $rowx = $pdf->GetX(); 
        $rowy = $pdf->GetY();
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'Data:', 0, 0, 'C');
        $pdf->MultiCell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->workSheet->participationEventDataJson->dates, 0, 'L');
        $pdf->Rect($rowx, $rowy, array_sum($tableHeadersWidths), $pdf->GetY() - $rowy);

        //Data:
        $rowx = $pdf->GetX(); 
        $rowy = $pdf->GetY();
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'Horário:', 0, 0, 'C');
        $pdf->MultiCell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->workSheet->participationEventDataJson->times, 0, 'L');
        $pdf->Rect($rowx, $rowy, array_sum($tableHeadersWidths), $pdf->GetY() - $rowy);

        //Data:
        $rowx = $pdf->GetX(); 
        $rowy = $pdf->GetY();
        $pdf->Cell($tableHeadersWidths[0], $rowHeight, 'Total carga horária:', 0, 0, 'C');
        $pdf->MultiCell($tableHeadersWidths[1], $rowHeight, $this->professorInfos->workSheet->participationEventDataJson->workTime, 0, 'L');
        $pdf->Rect($rowx, $rowy, array_sum($tableHeadersWidths), $pdf->GetY() - $rowy);

        $pdf->Ln();
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

        $finalText = 'Itapevi, ' . $formatEndDate(new \DateTime($this->professorInfos->workSheet->signatureDate));

        $pdf = $this->pdf;
        $pdf->Ln();
        $pdf->SetFont('freesans','',12);
        $pdf->Cell(0, 8, $finalText, 0, 1, $this->translateAlign($elDesc->align ?? ''));
    }

    public function professorSignatureField(object $elDesc)
    {
        $pdf = $this->pdf;
        
        $originalX = $pdf->GetX();
        $originalY = $pdf->GetY();

        if (!empty($this->professorInfos->workSheet->_signatures))
            foreach ($this->professorInfos->workSheet->_signatures as $signature) 
                if ($signature->docSignatureId === (int)$elDesc->docSignatureId)
                {
                    $elDesc->align = $elDesc->align ?? 'left';
                    $signX = $elDesc->align === "left" ? $pdf->GetX() : ($elDesc->align === "right" ? $pdf->GetPageWidth() - 120 : ($pdf->GetPageWidth() / 2) - 50);
                    $pdf->Image(IMG_SISEPI_LOGO_FILE, $signX, $pdf->GetY(), 20, 0);
                    $pdf->SetX($signX + 20);
                    $code = $signature->id;
                    $signatureDateTime = date_create($signature->signatureDateTime);
                    $signatureDateTimeStr = $signatureDateTime->format('d/m/Y H:i:s');
                    $authText = "Aceito eletronicamente pelo docente via sistema da Escola do Parlamento. Verifique a autenticidade desta assinatura em: " . \AUTH_ADDRESS . 
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
        $pdf->Cell(0, 8, $this->professorInfos->professor->name, 0, 1, $this->translateAlign($elDesc->align ?? ''));
        $pdf->Ln();
    }

    public function paymentValuesTable(object $elDesc)
    {
        $pdf = $this->pdf;
        $pdf->SetFont('freesans','B',14);
        $pdf->MultiCell(0, 8, $this->professorInfos->workSheet->paymentInfosJson->paymentLevelTables[$elDesc->tableId]->tableName, 0, 'C');

        $pdf->SetFont('freesans','',12);
        $pdf->SetFillColor(181, 230, 29);

        $tableWidths = [120, 40];
        $pdf->Cell($tableWidths[0], 8, 'Titulação', 1, 0, 'C', true);
        $pdf->Cell($tableWidths[1], 8, 'Valor', 1, 1, 'C', true);
        foreach ($this->professorInfos->workSheet->paymentInfosJson->paymentLevelTables[$elDesc->tableId]->levels as $paymentLevel)
        {
            $pdf->Cell($tableWidths[0], 8, 'Palestrante ' . $paymentLevel->name, 1, 0, 'C');
            $pdf->Cell($tableWidths[1], 8, formatDecimalToCurrency($paymentLevel->classTimeValue), 1, 1, 'C');
        }
        $pdf->Ln(10);
    }

    public function inssCompaniesProfessorWorksAt(object $elDesc)
    {
        $pdf = $this->pdf;
        $innerPageWidth = $elDesc->tableWidth ?? $pdf->GetPageWidth() - 50;

        $columnsWidth = [ $innerPageWidth * 0.5, $innerPageWidth * 0.2, $innerPageWidth * 0.15, $innerPageWidth * 0.15 ];

        $pdf->SetFont('freesans','B',10);
        $pdf->Cell($columnsWidth[0], 8, 'Empresa', 1, 0, 'C'); 
        $pdf->Cell($columnsWidth[1], 8, 'CNPJ', 1, 0, 'C'); 
        $pdf->Cell($columnsWidth[2], 8, 'Remuneração', 1, 0, 'C'); 
        $pdf->Cell($columnsWidth[3], 8, 'INSS retido', 1, 1, 'C'); 
        
        $pdf->SetFont('freesans','', 9);

        $truncateMaxWidth = function($string, $columnWidth) use ($pdf)
        {
            $maxTextLength = mb_strlen($string);	

			if ($pdf->GetStringWidth($string) > $columnWidth - 6)
				for ($c = 0; $c < mb_strlen($string); $c++)
					if ($pdf->GetStringWidth(mb_substr($string, 0, $c + 1)) > $columnWidth - 6)
					{
						$maxTextLength = $c;
						break;
					}

			return truncateText($string, $maxTextLength);
        };

        foreach ($this->professorInfos->workSheet->paymentInfosJson->companies as $c)
        {
            $pdf->Cell($columnsWidth[0], 8, $truncateMaxWidth($c->name, $columnsWidth[0]), 1, 0, 'L');
            $pdf->Cell($columnsWidth[1], 8, $c->cnpj ? Data\formatCNPJ($c->cnpj) : '', 1, 0, 'L');
            $pdf->Cell($columnsWidth[2], 8, $c->wage ? formatDecimalToCurrency((float)$c->wage) : '', 1, 0, 'L');
            $pdf->Cell($columnsWidth[3], 8, $c->collectedInss ? formatDecimalToCurrency((float)$c->collectedInss) : '', 1, 1, 'L');
        }
        $pdf->Ln(5);
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
        $professor = $this->professorInfos->professor;
        $event = $this->professorInfos->event;
        $workSheet = $this->professorInfos->workSheet;

        return
        [
            'professorName' => fn() => $professor->name,
            'professorRG' => fn() => $professor->personalDocsJson->rg . ' ' . $professor->personalDocsJson->rgIssuingAgency,
            'professorCPF' => fn() => Data\formatCPF($professor->personalDocsJson->cpf),
            'professorAddressCity' => fn() => $professor->homeAddressJson->city,
            'professorAddressStreet' => fn() => $professor->homeAddressJson->street,
            'professorAddressNumber' => fn() => $professor->homeAddressJson->number,
            'professorAddressNeighborhood' => fn() => $professor->homeAddressJson->neighborhood,
            'professorQualification' => fn() => $workSheet->paymentInfosJson->paymentLevelTables[$workSheet->paymentTableId]->levels[$workSheet->paymentLevelId]->name,
            'professorSubsAllowanceQualification' => fn() => isset($workSheet->paymentSubsAllowanceTableId, $workSheet->paymentSubsAllowanceLevelId) ?
                     $workSheet->paymentInfosJson->paymentLevelTables[$workSheet->paymentSubsAllowanceTableId]->levels[$workSheet->paymentSubsAllowanceLevelId]->name
                     :
                     '',
            'professorEventName' => fn() => isset($this->event) ? $event->name : $workSheet->participationEventDataJson->activityName,
            'professorClassDates' => fn() => $workSheet->participationEventDataJson->dates,
            'professorCollectInssFromDate' => fn() => date_create($workSheet->paymentInfosJson->inssPeriodBegin)->format('d/m/Y'), 
            'professorCollectInssToDate' => fn() => date_create($workSheet->paymentInfosJson->inssPeriodEnd)->format('d/m/Y'), 
            'professorPIS_PASEP' => fn() => !empty($professor->personalDocsJson->pis_pasep) ? $professor->personalDocsJson->pis_pasep : $this->pdf->Error('NIT/PIS/PASEP não informado!'),
            'professorPaymentValueAndFullText' => function() use ($professor, $event, $workSheet)
            {
                require_once __DIR__ . '/../Data/NumbersInFull.php';

                $classPayment = ($workSheet->paymentInfosJson->paymentLevelTables[$workSheet->paymentTableId]->levels[$workSheet->paymentLevelId]->classTimeValue)
                *
                ($workSheet->classTime)
                *
                ($workSheet->paymentInfosJson->professorTypes[$workSheet->professorTypeId]->paymentMultiplier);

                $subsAllowancePayment = 0;
                if (!is_null($workSheet->paymentSubsAllowanceTableId))
                {
                    $subsAllowancePayment = ($workSheet->paymentInfosJson->paymentLevelTables[$workSheet->paymentSubsAllowanceTableId]->levels[$workSheet->paymentSubsAllowanceLevelId]->classTimeValue)
                    *
                    ($workSheet->paymentSubsAllowanceClassTime);
                }

                return formatDecimalToCurrency($classPayment + $subsAllowancePayment) . ' (' . Data\NumbersInFull\moneyValueInFull($classPayment + $subsAllowancePayment) . ')';
            },
            'professorClassHoursAndMode' => function() use ($professor, $event, $workSheet)
            {
                $classTime = sprintf('%g', $workSheet->classTime) . ($workSheet->classTime > 1 ? ' horas-aula ' : ' hora-aula ');
                $mode = 'do tipo ' . $workSheet->paymentInfosJson->paymentLevelTables[$workSheet->paymentTableId]->tableTypeTagName;
                return $classTime . $mode;
            },
            'professorSubsAllowanceClassHoursAndMode' => function() use ($professor, $event, $workSheet)
            {
                if (is_null($workSheet->paymentSubsAllowanceTableId) || is_null($workSheet->paymentSubsAllowanceLevelId)) return '';

                $classTime = sprintf('%g', $workSheet->paymentSubsAllowanceClassTime) . ($workSheet->paymentSubsAllowanceClassTime > 1 ? ' horas-aula ' : ' hora-aula ');
                $mode = 'do tipo ' . $workSheet->paymentInfosJson->paymentLevelTables[$workSheet->paymentSubsAllowanceTableId]->tableTypeTagName;
                return $classTime . $mode;
            }
        ];
    } 
}
