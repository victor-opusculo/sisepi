<?php

namespace Model\VereadorMirim;

require_once __DIR__ . '/Document.php';
require_once __DIR__ . '/Student.php';
require_once __DIR__ . '/VmParent.php';

final class DocumentInfos
{
    public Document $document;
    public Student $vmStudent;
    public ?VmParent $vmParent;

    public function __construct(Document $doc, Student $stu, ?VmParent $par)
    {
        $this->document = $doc;
        $this->document->documentData = json_decode($doc->documentData);
        $this->vmStudent = $stu;
        $this->vmParent = $par;
    }
}