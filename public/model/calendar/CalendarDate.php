<?php

namespace SisEpi\Pub\Model\Calendar;

use mysqli;
use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../../../vendor/autoload.php';

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

    public ?array $childDates;
    public ?array $traits;

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getAllFromMonth(mysqli $conn, int $month, int $year) : array
    {
        $refDateTime = new \DateTime("$year-$month-01");
        $firstDay = $refDateTime->format("Y-m-d");
        $lastDay = $refDateTime->format("Y-m-t");

        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName('type'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('title'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('date'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('beginTime'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('styleJson'));

        $selector->setTable($this->databaseTable);

        $selector->addWhereClause($this->getWhereQueryColumnName('date') . ' >= ? ');
        $selector->addWhereClause(' AND ' . $this->getWhereQueryColumnName('date') . ' <= ? ');
        $selector->addWhereClause(" AND type != 'privatesimpleevent'");
        $selector->addValues('ss', [ $firstDay, $lastDay ]);

        $selector->setOrderBy(' date ASC, beginTime ASC ');

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function getAllFromDay(mysqli $conn, string $day) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName('id'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('type'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('title'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('description'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('date'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('beginTime'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('endTime'));
        $selector->addSelectColumn($this->getSelectQueryColumnName('styleJson'));
        $selector->setTable($this->databaseTable);
        
        $selector->addWhereClause($this->getWhereQueryColumnName('date') . ' = ?');
        $selector->addWhereClause(" AND type != 'privatesimpleevent'");
        $selector->addValue('s', $day);
        $selector->setOrderBy('beginTime ASC');

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function fetchChildDates(mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("*");
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause($this->getWhereQueryColumnName("parentId") . ' = ?');
        $selector->addValue('i', $this->properties->id->getValue());
        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);

        $this->childDates = array_map(fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function fetchTraits(mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('traits.id');
        $selector->addSelectColumn('traits.name');
        $selector->addSelectColumn('traits.description');
        $selector->addSelectColumn('traits.fileExtension');
        $selector->setTable('calendardatestraits');
        $selector->addJoin("INNER JOIN traits ON traits.id = calendardatestraits.traitId");
        $selector->addWhereClause("calendardatestraits.calendarDateId = ?");
        $selector->addValue('i', $this->properties->id->getValue());

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $this->traits = array_map( function($dr)
        {
            $trait = new \SisEpi\Model\Traits\EntityTrait();
            $trait->fillPropertiesFromDataRow($dr);
            return $trait;
        }, $drs);
    }

    public static function calendarCompareDateTimeFromEventsList($a, $b)
    {
        $dt1 = new \DateTime($a['date'] . ' ' . $a['beginTime']);
        $dt2 = new \DateTime($b['date'] . ' ' . $b['beginTime']);
        
        return $dt1 <=> $dt2;
    }
}