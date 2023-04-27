<?php

namespace SisEpi\Model\Ods;

use Exception;
use mysqli;
use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\Exceptions\DatabaseEntityNotFound;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../exceptions.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class OdsRelation extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('odsRelationId', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('txtName', null, DataProperty::MYSQL_STRING),
            'year' => new DataProperty('numYear', null, DataProperty::MYSQL_INT),
            'odsCodes' => new DataProperty('hidOdsCodes', null, DataProperty::MYSQL_STRING),
            'eventId' => new DataProperty('numEventId', null, DataProperty::MYSQL_INT)
        ];
    }

    public array $goals = [];
    public array $odsAndGoalsStructured = [];
    public array $codesArray = [];

    protected string $databaseTable = 'odsrelations';
    protected string $formFieldPrefixName = 'odsrelations';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function fillPropertiesFromDataRow($dataRow)
    {
        parent::fillPropertiesFromDataRow($dataRow);
        if (isset($this->odsCodes))
            $this->codesArray = json_decode($this->odsCodes);
    } 

    public function getOdsData(mysqli $conn) : ?array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('value')
        ->setTable('settings')
        ->addWhereClause("name = 'ODS_DATA' ");

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        if (isset($dr) && isset($dr['value']))
            return json_decode($dr['value']);
        else
            throw new DatabaseEntityNotFound("Dados de ODS não encontrados!", 'settings');
    }

    public function getSingle(mysqli $conn)
    {
        $selector = $this->getGetSingleSqlSelector();
        $selector
        ->addSelectColumn('events.name AS eventName')
        ->addJoin("LEFT JOIN events ON events.id = {$this->databaseTable}.eventId ");

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new DatabaseEntityNotFound("Relação ODS não encontrada.", $this->databaseTable);
    }

    public function getFromEvent(mysqli $conn) : self
    {
        $selector = (new SqlSelector) 
        ->addSelectColumn("{$this->databaseTable}.*")
        ->addSelectColumn('events.name AS eventName')
        ->setTable($this->databaseTable)
        ->addJoin("LEFT JOIN events ON events.id = {$this->databaseTable}.eventId ")
        ->addWhereClause(" {$this->databaseTable}.eventId = ? ")
        ->addValue('i', $this->properties->eventId->getValue());

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new DatabaseEntityNotFound("Relação ODS não encontrada.", $this->databaseTable);
    }

    public function getCount(mysqli $conn, string $searchKeywords) : int
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector = $selector
            ->addWhereClause(' MATCH (name) AGAINST (?) ')
            ->addValue('s', $searchKeywords);
        }

        return (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $_orderBy, $searchKeywords) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn($this->databaseTable. '.*')
        ->addSelectColumn("JSON_LENGTH(odsCodes) AS goalsNumber")
        ->addSelectColumn("events.name AS eventName")
        ->setTable($this->databaseTable)
        ->addJoin("LEFT JOIN events ON events.id = {$this->databaseTable}.eventId ");

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector = $selector
            ->addWhereClause("MATCH ({$this->databaseTable}.name) AGAINST (?) ")
            ->addValue('s', $searchKeywords);
        }

	    $calc_page = ($page - 1) * $numResultsOnPage;
        $selector = $selector 
        ->setLimit(' ?,? ')
        ->addValues('ii', [$calc_page, $numResultsOnPage]);

        switch ($_orderBy)
        {
            case 'id':
                $selector = $selector->setOrderBy($this->databaseTable . '.id ASC ');
                break;
            case 'name':
                $selector = $selector->setOrderBy($this->databaseTable . '.name ASC ');
                break;
            case 'goals':
                $selector = $selector->setOrderBy("goalsNumber ASC");
                break;
            case 'year':
            default:
                $selector = $selector->setOrderBy('year DESC, name ASC ');
                break;
        }

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function getAllFromYear(mysqli $conn) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn("{$this->databaseTable}.*")
        ->addSelectColumn("events.name AS eventName")
        ->setTable($this->databaseTable)
        ->addJoin("LEFT JOIN events ON events.id = {$this->databaseTable}.eventId ")
        ->addWhereClause("{$this->databaseTable}.year = ? ")
        ->addValue('i', $this->properties->year->getValue());

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        $this->checkForRepeatedEventId($conn);
        return 0;
    }

    public function beforeDatabaseUpdate(mysqli $conn): int
    {
        $this->checkForRepeatedEventId($conn);
        return 0;
    }

    public function getGoalsObjects(array $odsData) : array
    {
        $output = [];

        $codes = json_decode($this->properties->odsCodes->getValue());
        $codes = array_map( fn($code) => [ ...explode('.', $code) ], $codes);

        foreach ($odsData as $ods)
        {
            foreach ($codes as $currentCode)
            {
                [ $number, $id ] = $currentCode;

                if ((string)$number === (string)$ods->number)
                    foreach ($ods->goals as $goal)
                        if ((string)$goal->id === (string)$id)
                            $output[] = (object)
                            [
                                'number' => $number,
                                'id' => $id,
                                'description' => $goal->description
                            ];
            }
        }

        return $output;
    }

    public function getStructuredGoalsInfos(array $odsData) : array
    {
        $codesArray = json_decode($this->odsCodes ?? '');

        $goalsObjects = [];
        foreach ($codesArray as $code)
        {
            [ $number, $id ] = explode('.', $code);
            if (!isset($goalsObjects[$number]))
            {
                $odsArr = array_filter($odsData, fn($o) => $o->number == $number);
                $ods = array_shift($odsArr);
                $goalArr = array_filter($ods->goals, fn($g) => $g->id == $id);
                $goal = array_shift($goalArr);
                $goalsObjects[$number] = 
                [
                    'description' => $ods->description,
                    'goals' => 
                    [
                        $id => 
                        [
                            'description' => $goal->description
                        ]
                    ]
                ];
            }
            else
            {
                if (!isset($goalsObjects[$number]['goals'][$id]))
                {
                    $odsArr = array_filter($odsData, fn($o) => $o->number == $number);
                    $ods = array_shift($odsArr);
                    $goalArr = array_filter($ods->goals, fn($g) => $g->id == $id);
                    $goal = array_shift($goalArr);
                    $goalsObjects[$number]['goals'][$id] =
                    [
                        'description' => $goal->description
                    ];
                }
            }
        }

        return $goalsObjects;
    }

    public function fetchOdsAndGoalsStructured(mysqli $conn)
    {
        $odsData = $this->getOdsData($conn);
        $odsGoals = $this->getStructuredGoalsInfos($odsData);
        $this->odsAndGoalsStructured = $odsGoals;
    }

    private function checkForRepeatedEventId(mysqli $conn)
    {
        $thisEventId =  $this->properties->eventId->getValue();
        if (!isset($thisEventId)) return;

        $selector = (new SqlSelector)
        ->addSelectColumn("{$this->databaseTable}.id")
        ->addSelectColumn("{$this->databaseTable}.eventId")
        ->addSelectColumn('events.name AS eventName')
        ->setTable($this->databaseTable)
        ->addJoin("INNER JOIN events ON events.id = {$this->databaseTable}.eventId ")
        ->addWhereClause('eventId = ?')
        ->addValue('i', $thisEventId);

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dr) && ($dr['id'] != $this->properties->id->getValue())) throw new Exception("Erro: O evento '{$dr['eventName']}' já tem uma relação ODS definida.");
    }

}