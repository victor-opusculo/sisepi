<?php

namespace SisEpi\Model;

class AnsweredEventSurvey
{
    private object $surveyObject;

    public function __construct(object $surveyObject)
    {
        $this->surveyObject = $surveyObject;
    }

    public function allItemsAsObject()
    {
        return [...$this->itemsFromBlockAsObject('head'), ...$this->itemsFromBlockAsObject('body'), ...$this->itemsFromBlockAsObject('foot')];
    }

    public function itemsFromBlockAsObject(string $blockName)
    {
        if ($this->surveyObject->$blockName)
        {
            foreach ($this->surveyObject->$blockName as $item)
            {
                $answeredItemObj = clone $item;
                $answeredItemObj->formattedAnswer = $this->formatAnswer($item);
                yield $answeredItemObj;
            }
        }
        else
            return [];
    }

    public function hasBlock($blockName)
    {
        return isset($this->surveyObject->$blockName);
    }

    private function formatAnswer(object $itemObject)
    {
        switch ($itemObject->type)
        {
            case 'fiveStarRating': return $this->formatFiveStarRatingAnswer($itemObject->value);
            case 'checkList': return $this->formatCheckListAnswer($itemObject->checkList);
            case 'textArea':
            case 'shortText': return $itemObject->value;
            case 'yesNo':
            default: return $this->formatYesNoAnswer($itemObject->value);
        }
    }

    private function formatFiveStarRatingAnswer($value)
    {
        switch ($value)
        {
            case '1': return '1 - Muito Ruim';
            case '2': return '2 - Ruim';
            case '3': return '3 - Regular';
            case '4': return '4 - Bom';
            case '5': return '5 - Excelente';
            case '0': 
            case '': 
            default: return 'Sem resposta';
        }
    }

    private function formatCheckListAnswer($itemCheckListProperty)
    {
        $checked = [];
        foreach ($itemCheckListProperty as $checkItem)
            if (isset($checkItem->value) && $checkItem->value == '1')
                $checked[] = $checkItem->name;
        
        return implode(', ', $checked);
    }

    private function formatYesNoAnswer($value)
    {
        switch ($value)
        {
            case '0': return 'Não';
            case '1': return 'Sim';
            case '': 
            default: return 'Sem resposta';
        }
    }
}