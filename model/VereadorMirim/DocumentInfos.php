<?php

namespace SisEpi\Model\VereadorMirim;

require_once __DIR__ . '/../../vendor/autoload.php';

final class DocumentInfos
{
    public Document $document;
    public Student $vmStudent;
    public ?VmParent $vmParent;
    public ?School $vmSchool;

    public function __construct(Document $doc, Student $stu, ?VmParent $par, ?School $school)
    {
        $this->document = $doc;
        $this->document->documentData = json_decode($doc->documentData ?? '');
        $this->vmStudent = $stu;
        $this->vmParent = $par;
        $this->vmSchool = $school;
    }
}