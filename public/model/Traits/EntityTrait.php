<?php
//Public
namespace SisEpi\Pub\Model\Traits;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../model/exceptions.php';

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\Exceptions\DatabaseEntityNotFound;
use mysqli;
use SisEpi\Model\SqlSelector;

class EntityTrait extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('traitId', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('txtName', 'Traço não nomeado', DataProperty::MYSQL_STRING),
            'description' => new DataProperty('txtDescription', '', DataProperty::MYSQL_STRING),
            'fileExtension' => new DataProperty('hidFileExtension', null, DataProperty::MYSQL_STRING)
        ];
    }

    public string $fileUploadFieldName = 'fileTraitIconFile';
    protected string $databaseTable = 'traits';
    protected string $formFieldPrefixName = 'traits';
    protected array $primaryKeys = ['id'];

    public function getCount(mysqli $conn, $searchKeywords) : int
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('COUNT(*)');
        $selector->setTable($this->databaseTable);
        
        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(' MATCH (name, description) AGAINST (?) ');
            $selector->addValue('s', $searchKeywords);
        }

        return $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $orderBy, $searchKeywords)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('id');
        $selector->addSelectColumn('name');
        $selector->addSelectColumn('fileExtension');
        $selector->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(' MATCH (name, description) AGAINST (?) ');
            $selector->addValue('s', $searchKeywords);
        }

        switch ($orderBy)
        {
            case 'name':
                $selector->setOrderBy('name ASC');
                break;
            case 'id':
            default:
                $selector->setOrderBy('id ASC');
                break;
        }

        $calc_page = ($page - 1) * $numResultsOnPage;
        $selector->setLimit(' ?,? ');
        $selector->addValues('ii', [ $calc_page, $numResultsOnPage ]);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = [];
        foreach ($drs as $dr)
        {
            $new = new EntityTrait();
            $new->fillPropertiesFromDataRow($dr);
            $output[] = $new;
        }

        return $output;
    }

    public function getSingleDataRow(mysqli $conn) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->databaseTable . '.*');
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause("{$this->databaseTable}.id = ? ");
        $selector->addValue('i', $this->id);

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        if (isset($dr))
            return $dr;
        else
            throw new DatabaseEntityNotFound("Traço não encontrado!", $this->databaseTable);
    }

    public function getCalendarEventTraits(mysqli $conn, $calEventId)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->databaseTable . '.*');
        $selector->setTable("calendardatestraits");
        $selector->addJoin("INNER JOIN {$this->databaseTable} ON {$this->databaseTable}.id = calendardatestraits.traitId");
        $selector->addWhereClause("calendardatestraits.calendarDateId = ? ");
        $selector->addValue('i', $calEventId);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = [];
        foreach ($drs as $dr)
            $output[] = $this->newInstanceFromDataRow($dr);

        return $output;
    }

    public function getCertifiableEventTraits(mysqli $conn, $eventDateId)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->databaseTable . '.*');
        $selector->setTable("eventdatestraits");
        $selector->addJoin("INNER JOIN {$this->databaseTable} ON {$this->databaseTable}.id = eventdatestraits.traitId");
        $selector->addWhereClause("eventdatestraits.eventDateId = ? ");
        $selector->addValue('i', $eventDateId);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = [];
        foreach ($drs as $dr)
            $output[] = $this->newInstanceFromDataRow($dr);

        return $output;
    }

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new EntityTrait();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function fillPropertiesFromFormInput($post, $files = null) { }
    public function save(mysqli $conn) { }
    public function delete(mysqli $conn) { }

}