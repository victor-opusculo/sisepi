<?php
//Public

namespace SisEpi\Public\Model\Events;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Public\Model\Professors\Professor;
use SisEpi\Public\Model\Traits\EntityTrait;
use mysqli;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../../../vendor/autoload.php';

class EventDate extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('id', null, DataProperty::MYSQL_INT),
            'date' => new DataProperty('date', null, DataProperty::MYSQL_STRING),
            'beginTime' => new DataProperty('beginTime', null, DataProperty::MYSQL_STRING),
            'endTime' => new DataProperty('endTime', null, DataProperty::MYSQL_STRING),
            'name' => new DataProperty('name', null, DataProperty::MYSQL_STRING),
            'presenceListNeeded' => new DataProperty('presenceListEnabled', null, DataProperty::MYSQL_INT),
            'presenceListPassword' => new DataProperty('presenceListPassword', null, DataProperty::MYSQL_STRING),
            'locationId' => new DataProperty('locationId', null, DataProperty::MYSQL_INT),
            'locationInfosJson' => new DataProperty('locationInfosJson', null, DataProperty::MYSQL_STRING),
            'eventId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'checklistId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'calendarStyleJson' => new DataProperty('calendarStyleJson', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'eventdates';
    protected string $formFieldPrefixName = '';
    protected array $primaryKeys = ['id'];

    public array $professors = [];
    public array $traits = [];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new EventDate();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getSingle(mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("{$this->databaseTable}.*");
        $selector->addSelectColumn("(concat(eventdates.date, ' ', eventdates.beginTime)) as fullDateTime ");
        $selector->addSelectColumn("(CONCAT( DATE, ' ', beginTime ) < NOW() AND DATE_ADD( CONCAT( DATE, ' ', endTime ) , INTERVAL 30 MINUTE) > NOW()) AS isPresenceListOpen");
        $selector->addSelectColumn("eventlocations.name as locationName");
        $selector->setTable($this->databaseTable);
        $selector->addJoin("LEFT JOIN eventlocations ON eventlocations.id = {$this->databaseTable}.locationId");

        $selector->addWhereClause("{$this->databaseTable}.id = ?");
        $selector->addValue('i', $this->id);
        $selector->setOrderBy("fullDateTime ASC");

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new \SisEpi\Model\Exceptions\DatabaseEntityNotFound("Data de evento não localizada.", $this->databaseTable);
    }

    public function getAllFromMonth(mysqli $conn, int $month, int $year) : array
    {
        $refDateTime = new \DateTime("$year-$month-01");
        $firstDay = $refDateTime->format("Y-m-d");
        $lastDay = $refDateTime->format("Y-m-t");

        $selector = new SqlSelector();
        $selector->addSelectColumn('events.name as eventName');
        $selector->addSelectColumn("{$this->databaseTable}.date");
        $selector->addSelectColumn("{$this->databaseTable}.name");
        $selector->addSelectColumn("{$this->databaseTable}.beginTime");
        $selector->addSelectColumn("eventlocations.calendarInfoBoxStyleJson");
        $selector->setTable($this->databaseTable);

        $selector->addJoin("INNER JOIN events ON events.id = {$this->databaseTable}.eventId");
        $selector->addJoin("LEFT JOIN eventlocations ON eventlocations.id = {$this->databaseTable}.locationId");

        $selector->addWhereClause($this->getWhereQueryColumnName('date') . ' >= ?');
        $selector->addWhereClause('AND ' . $this->getWhereQueryColumnName('date') . ' <= ?');
        $selector->addValues('ss', [ $firstDay, $lastDay ]);

        $selector->setOrderBy("{$this->databaseTable}.date ASC");

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function getAllFromDay(mysqli $conn, string $day) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("events.id as eventId");
        $selector->addSelectColumn("events.name as eventName");
        $selector->addSelectColumn("{$this->databaseTable}.id as eventDateId");
        $selector->addSelectColumn("{$this->databaseTable}.date");
        $selector->addSelectColumn("{$this->databaseTable}.name");
        $selector->addSelectColumn("{$this->databaseTable}.beginTime");
        $selector->addSelectColumn("{$this->databaseTable}.endTime");
        $selector->addSelectColumn("eventlocations.name as locationName");
        $selector->addSelectColumn("eventlocations.calendarInfoBoxStyleJson");
        $selector->setTable($this->databaseTable);

        $selector->addJoin("INNER JOIN events ON events.id = {$this->databaseTable}.eventId");
        $selector->addJoin("LEFT JOIN eventlocations ON eventlocations.id = {$this->databaseTable}.locationId");

        $selector->addWhereClause("{$this->databaseTable}.date = ?");
        $selector->addValue('s', $day);
        $selector->setOrderBy("{$this->databaseTable}.beginTime ASC");

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function fetchSubEntities(mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('professorId');
        $selector->setTable('eventdatesprofessors');
        $selector->addWhereClause('eventdatesprofessors.eventDateId = ? ');
        $selector->addValue('i', $this->properties->id->getValue());

        $profIds = $selector->run($conn, SqlSelector::RETURN_ALL_NUM);
        $getter = new Professor();
        $getter->setCryptKey($this->encryptionKey);
        foreach ($profIds as $id)
        {
            $getter->id = $id[0];
            $new = $getter->getSingleBasic($conn);
            $this->professors[] = $new;
        }

        $selector = new SqlSelector();
        $selector->addSelectColumn('traitId');
        $selector->setTable('eventdatestraits');
        $selector->addWhereClause('eventdatestraits.eventDateId = ? ');
        $selector->addValue('i', $this->properties->id->getValue());

        $traitsIds = $selector->run($conn, SqlSelector::RETURN_ALL_NUM);
        $getter = new EntityTrait();
        foreach ($traitsIds as $id)
        {
            $getter->id = $id[0];
            $new = $getter->getSingle($conn);
            $this->traits[] = $new;
        }
    }

    public function fetchTraits(mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('traitId');
        $selector->setTable('eventdatestraits');
        $selector->addWhereClause('eventdatestraits.eventDateId = ? ');
        $selector->addValue('i', $this->properties->id->getValue());

        $traitsIds = $selector->run($conn, SqlSelector::RETURN_ALL_NUM);
        $getter = new EntityTrait();
        foreach ($traitsIds as $id)
        {
            $getter->id = $id[0];
            $new = $getter->getSingle($conn);
            $this->traits[] = $new;
        }
    }

    public function save(mysqli $conn)
    { }

    public function delete(mysqli $conn)
    { }

    public function getProfessorsNames()
    {
        return implode(", ", array_map(fn($p) => $p->name, $this->professors));
    }

    public function getDateURL(mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("JSON_UNQUOTE(JSON_EXTRACT(locationInfosJson, '$.url'))");
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause("{$this->databaseTable}.id = ?");
        $selector->addValue('i', $this->id);

        return $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }
}