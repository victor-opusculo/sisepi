<?php

final class ProfessorDocInfos
{
    private object $professor;
    private object $event;
    private object $workSheet;

    public function __construct($professorObj, $eventObj, $workSheetObj)
    {
        $this->professor = $professorObj;
        $this->event = $eventObj;
        $this->workSheet = $workSheetObj;
    }

    public function getGeneratedContentTagsTable() : array
    {
        return
        [
            'professorName' => fn() => $this->professor->name,
            'professorRG' => fn() => $this->professor->personalDocsJson->rg,
            'professorCPF' => fn() => $this->professor->personalDocsJson->cpf,
            'professorAddressCity' => fn() => $this->professor->homeAddressJson->city,
            'professorAddressStreet' => fn() => $this->professor->homeAddressJson->street,
            'professorAddressNumber' => fn() => $this->professor->homeAddressJson->number,
            'professorAddressNeighborhood' => fn() => $this->professor->homeAddressJson->neighborhood,
            'professorQualification' => fn() => $this->workSheet->paymentInfosJson->paymentLevelTables[$this->workSheet->paymentTableId]->levels[$this->workSheet->paymentLevelId]->name,
            'professorSubsAllowanceQualification' => fn() => $this->workSheet->paymentInfosJson->paymentLevelTables[$this->workSheet->paymentSubsAllowanceTableId]->levels[$this->workSheet->paymentSubsAllowanceLevelId]->name,
            'professorEventName' => fn() => $this->event->name,
            'professorClassDates' => fn() => $this->workSheet->participationEventDataJson->dates,
            'professorPaymentValueAndFullText' => function()
            {
                require_once __DIR__ . '/../Data/NumbersInFull.php';

                $classPayment = ($this->workSheet->paymentInfosJson->paymentLevelTables[$this->workSheet->paymentTableId]->levels[$this->workSheet->paymentLevelId]->classTimeValue)
                *
                ($this->workSheet->classTime)
                *
                ($this->workSheet->paymentInfosJson->professorTypes[$this->workSheet->professorTypeId]->paymentMultiplier);

                $subsAllowancePayment = 0;
                if (!is_null($this->workSheet->paymentSubsAllowanceTableId))
                {
                    $subsAllowancePayment = ($this->workSheet->paymentInfosJson->paymentLevelTables[$this->workSheet->paymentSubsAllowanceTableId]->levels[$this->workSheet->paymentSubsAllowanceLevelId]->classTimeValue)
                    *
                    ($this->workSheet->paymentSubsAllowanceClassTime);
                }

                return formatDecimalToCurrency($classPayment + $subsAllowancePayment) . ' (' . Data\NumbersInFull\moneyValueInFull($classPayment + $subsAllowancePayment) . ')';
            },
            'professorClassHoursAndMode' => function()
            {
                $classTime = sprintf('%g', $this->workSheet->classTime) . ($this->workSheet->classTime > 1 ? ' horas-aula ' : ' hora-aula ');
                $mode = 'do tipo ' . $this->workSheet->paymentInfosJson->paymentLevelTables[$this->workSheet->paymentTableId]->tableTypeTagName;
                return $classTime . $mode;
            },
            'professorSubsAllowanceClassHoursAndMode' => function()
            {
                if (is_null($this->workSheet->paymentSubsAllowanceTableId)) return '';

                $classTime = sprintf('%g', $this->workSheet->paymentSubsAllowanceClassTime) . ($this->workSheet->paymentSubsAllowanceClassTime > 1 ? ' horas-aula ' : ' hora-aula ');
                $mode = 'do tipo ' . $this->workSheet->paymentInfosJson->paymentLevelTables[$this->workSheet->paymentSubsAllowanceTableId]->tableTypeTagName;
                return $classTime . $mode;
            }
        ];
    } 

}