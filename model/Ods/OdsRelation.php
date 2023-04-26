<?php

namespace SisEpi\Model\Ods;

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

    protected string $databaseTable = 'odsrelations';
    protected string $formFieldPrefixName = 'odsrelations';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
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
        ->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector = $selector
            ->addWhereClause('MATCH (name) AGAINST (?) ')
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

    public function fetchGoals(mysqli $conn)
    {
        $odsData = $this->getOdsData($conn);
        $goals = $this->getGoalsObjects($odsData);
        $this->goals = $goals;
    }

}