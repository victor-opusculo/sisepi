<?php
namespace SisEpi\Model\VereadorMirim;

require_once __DIR__ . '/DocumentInfos.php';
require_once __DIR__ . '/Document.php';

final class DocumentConditionChecker
{
    private \SisEpi\Model\VereadorMirim\DocumentInfos $docInfos;

    public function __construct(\SisEpi\Model\VereadorMirim\DocumentInfos $docInfos)
    {
        $this->docInfos = $docInfos;
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

    private function requiresAccessibility() : bool
    {
        $info = (string)$this->docInfos->vmStudent->studentDataJson->accessibilityRequired;
        return $info !== '' && $info !== null;
    }
}