<?php

namespace SisEpi\Model\Events;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;

require_once __DIR__ . '/../../vendor/autoload.php';

class EventCompletedTest extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('hidTestId', null, DataProperty::MYSQL_INT),
            'subscriptionId' => new DataProperty('hidSubscriptionId', null, DataProperty::MYSQL_INT),
            'email' => new DataProperty('hidEmail', null, DataProperty::MYSQL_STRING, true),
            'eventId' => new DataProperty('hidEventId', null, DataProperty::MYSQL_INT),
            'testData' => new DataProperty('hidTestDataJson', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'eventcompletedtests';
    protected string $formFieldPrefixName = 'eventcompletedtests';
    protected array $primaryKeys = ['id' ];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }
}