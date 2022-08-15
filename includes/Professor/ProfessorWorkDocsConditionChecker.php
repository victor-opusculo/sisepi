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
            if (method_exists($this, $condition))
                return $carry && $this->$condition();
            else
                return $carry && false;
        }, true);
    }

    public function collectInss()
    {
        return $this->professorInfos->workSheet->paymentInfosJson->collectInss && 
            !empty($this->professorInfos->professor->inssCollectInfosJson->periodBegin) &&
            !empty($this->professorInfos->professor->inssCollectInfosJson->periodEnd) &&
            !empty($this->professorInfos->professor->personalDocsJson->pis_pasep);
    }
}