<?php

namespace Model\VereadorMirim;

use DataEntity;
use DataProperty;
use mysqli;
use SqlSelector;

require_once __DIR__ . '/../DataEntity.php';

class Legislature extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('vmLegislatureId', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('txtName', 'Legislatura não nomeada', DataProperty::MYSQL_STRING),
            'begin' => new DataProperty('dateBegin', null, DataProperty::MYSQL_STRING),
            'end' => new DataProperty('dateEnd', null, DataProperty::MYSQL_STRING)
        ];
    }
    
    protected string $databaseTable = 'vereadormirimlegislatures';
    protected string $formFieldPrefixName = 'vmlegislatures';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new Legislature();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getCount(mysqli $conn, $searchKeywords) : int
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('COUNT(*)');
        $selector->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" MATCH (name) AGAINST (?) ");
            $selector->addValue('s', $searchKeywords);
        }

        $count = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return (int)$count;
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $orderBy, $searchKeywords) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('id');
        $selector->addSelectColumn('name');
        $selector->addSelectColumn('begin');
        $selector->addSelectColumn('end');

        $selector->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" MATCH (name) AGAINST (?) ");
            $selector->addValue('s', $searchKeywords);
        }

        switch ($orderBy)
        {
            case 'name': $selector->setOrderBy('name ASC'); break;
            case 'beginDateAsc': $selector->setOrderBy('begin ASC'); break;
            case 'beginDateDesc':
            default:
                $selector->setOrderBy('begin DESC'); break;
        }

        $calc_page = ($page - 1) * $numResultsOnPage;
        $selector->setLimit(' ?,? ');
        $selector->addValues('ii', [ $calc_page, $numResultsOnPage] );

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = [];
        foreach ($drs as $dr)
        {
            $new = new Legislature();
            $new->fillPropertiesFromDataRow($dr);
            $output[] = $new;
        }

        return $output;
    }
}