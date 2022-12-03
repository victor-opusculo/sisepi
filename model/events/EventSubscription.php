<?php

namespace Model\Events;

use DataEntity;
use DataProperty;
use mysqli;
use SqlSelector;

require_once __DIR__ . '/../DataEntity.php';

class EventSubscription extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('eventSubscriptionId', null, DataProperty::MYSQL_INT, false),
            'eventId' => new DataProperty('eventId', null, DataProperty::MYSQL_INT, false),
            'name' => new DataProperty('txtName', null, DataProperty::MYSQL_STRING, true),
            'email' => new DataProperty('txtEmail', null, DataProperty::MYSQL_STRING, true),
            'subscriptionDataJson' => new DataProperty('', null, DataProperty::MYSQL_STRING, true),
            'subscriptionDate' => new DataProperty('', null, DataProperty::MYSQL_STRING, false)
        ];
    }

    protected string $databaseTable = 'subscriptionstudentsnew';
    protected string $formFieldPrefixName = 'subscriptionstudents';
    protected array $primaryKeys = ['id'];

    private ?object $studentDataObject; 

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new EventSubscription();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new; 
    }

    public function fillPropertiesFromDataRow($dataRow)
    {
        parent::fillPropertiesFromDataRow($dataRow);
        $this->studentDataObject = json_decode($this->subscriptionDataJson);
    }

    public function getQuestions()
    {
        return $this->studentDataObject->questions ?? [];
    }

    public function getTerms()
    {
        return $this->studentDataObject->terms  ?? [];
    }

    public function getMultipleFromEvent(mysqli $conn) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("{$this->databaseTable}.id");
        $selector->addSelectColumn("{$this->databaseTable}.eventId");
        $selector->addSelectColumn("aes_decrypt({$this->databaseTable}.name, '{$this->encryptionKey}') AS name ");
        $selector->addSelectColumn("aes_decrypt({$this->databaseTable}.email, '{$this->encryptionKey}') AS email ");
        $selector->addSelectColumn("aes_decrypt({$this->databaseTable}.subscriptionDataJson, '{$this->encryptionKey}') AS subscriptionDataJson ");
        $selector->addSelectColumn("{$this->databaseTable}.subscriptionDate");

        $selector->addSelectColumn("events.name AS eventName ");
        $selector->setTable($this->databaseTable);
        $selector->addJoin("INNER JOIN events ON events.id = {$this->databaseTable}.eventId ");
        $selector->addWhereClause("{$this->databaseTable}.eventId = ? ");
        $selector->addValue('i', $this->properties->eventId->getValue());

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);

        $output = [];
        foreach ($drs as $dr)
            $output[] = $this->newInstanceFromDataRow($dr);

        return $output;
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        $this->properties->subscriptionDate->setValue(date('Y-m-d H:i:s'));
        return 0;
    }
}