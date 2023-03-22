<?php

namespace SisEpi\Model\Events;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use mysqli;

require_once __DIR__ . '/../DataEntity.php';
require_once __DIR__ . '/../database/events.uploadFiles.php';

class EventAttachment extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'eventId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'fileName' => new DataProperty('', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'eventattachments';
    protected string $formFieldPrefixName = '';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new EventAttachment();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        if (uploadEventFile($this->properties->eventId->getValue(), $this->postFiles, $this->otherProperties->fileInputElementName))
        {
            $this->properties->fileName->setValue($this->postFiles[$this->otherProperties->fileInputElementName]["name"]);
            return 1;
        }
        return 0;
    }

    public function beforeDatabaseDelete(mysqli $conn): int
    {
        $stmt = $conn->prepare("SELECT fileName from eventattachments where id = ?");
        $stmt->bind_param("i", $this->properties->id->getValue());
        $stmt->execute();
        $results = $stmt->get_result();
        $stmt->close();
        $row = $results->fetch_assoc();
        $fileNameToDelete = $row["fileName"];

        if ($fileNameToDelete)
            if (deleteEventFile($this->properties->eventId->getValue(), $fileNameToDelete))
                return 1;

        return 0;
    }
}