<?php

namespace SisEpi\Model\Calendar;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;

require_once __DIR__ . '/../DataEntity.php';

class CalendarDate extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('calendarEventId', null, DataProperty::MYSQL_INT),
            'parentId' => new DataProperty('calendarParentId', null, DataProperty::MYSQL_INT),
            'type' => new DataProperty('radType', null, DataProperty::MYSQL_STRING),
            'title' => new DataProperty('txtName', null, DataProperty::MYSQL_STRING),
            'description' => new DataProperty('txtDescription', null, DataProperty::MYSQL_STRING),
            'date' => new DataProperty('dateEventDate', null, DataProperty::MYSQL_STRING),
            'beginTime' => new DataProperty('timeBeginTime', null, DataProperty::MYSQL_STRING),
            'endTime' => new DataProperty('timeEndTime', null, DataProperty::MYSQL_STRING),
            'styleJson' => new DataProperty('styleJson', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'calendardates';
    protected string $formFieldPrefixName = 'calendardates';
    protected array $primaryKeys = [ 'id' ];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }
}