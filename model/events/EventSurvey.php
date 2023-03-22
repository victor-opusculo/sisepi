<?php
namespace SisEpi\Model\Events;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../DataEntity.php';

class EventSurvey extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'eventId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'subscriptionId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'studentEmail' => new DataProperty('', null, DataProperty::MYSQL_STRING, true),
            'surveyJson' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'registrationDate' => new DataProperty('', null, DataProperty::MYSQL_STRING),
        ];
    }

    protected string $databaseTable = 'eventsurveys';
    protected string $formFieldPrefixName = 'eventsurveys';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new EventSurvey();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getAllOfEvent(\mysqli $conn, $eventId)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName('id'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('eventId'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('subscriptionId'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('studentEmail'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('surveyJson'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('registrationDate'));
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause(" {$this->databaseTable}.eventId = ? ");
        $selector->addValue('i', $eventId);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = array_map( fn($dr) => $this->newInstanceFromDataRow($dr) , $drs);
        return $output;
    }
}