<?php

namespace Professor;

final class ProfessorDocInfos
{
    public object $professor;
    public ?object $event;
    public object $workSheet;

    public function __construct($professorObj, $eventObj, $workSheetObj)
    {
        $this->professor = $professorObj;
        $this->event = $eventObj;
        $this->workSheet = $workSheetObj;
    }
}