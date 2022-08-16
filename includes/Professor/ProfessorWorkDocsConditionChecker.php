<?php
namespace Professor;

require_once __DIR__ . '/ProfessorDocInfos.php';

final class ProfessorWorkDocsConditionChecker
{
    private ProfessorDocInfos $professorInfos;

    public function __construct(ProfessorDocInfos $profInfos)
    {
        $this->professorInfos = $profInfos;
    }

    public function CheckConditions(array $conditionList) : bool
    {
        return array_reduce($conditionList, function($carry, $condition)
        {
            $conditionNameOnly = $condition[0] === '~' ? substr($condition, 1) : $condition;
            if (method_exists($this, $conditionNameOnly))
                return $condition[0] === '~' ? $carry && !$this->$conditionNameOnly() : $carry && $this->$conditionNameOnly();
            else
                return $carry && false;
        }, true);
    }

    public function collectInss()
    {
        return $this->professorInfos->workSheet->paymentInfosJson->collectInss && 
            !empty($this->professorInfos->workSheet->paymentInfosJson->inssPeriodBegin) &&
            !empty($this->professorInfos->workSheet->paymentInfosJson->inssPeriodEnd) &&
            !empty($this->professorInfos->professor->personalDocsJson->pis_pasep);
    }

    public function paySubsistenceAllowance()
    {
        return isset($this->professorInfos->workSheet->paymentSubsAllowanceTableId,
                    $this->professorInfos->workSheet->paymentSubsAllowanceLevelId,
                    $this->professorInfos->workSheet->paymentSubsAllowanceClassTime);
    }
}