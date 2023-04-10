<?php
namespace SisEpi\Model\Budget;


require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../includes/SearchById.php";


use DateTime;
use mysqli;
use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\SqlSelector;

class BudgetEntry extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('budgetEntryId', null, DataProperty::MYSQL_INT),
            'date' => new DataProperty('dateDate', null, DataProperty::MYSQL_STRING),
            'category' => new DataProperty('selCategory', null, DataProperty::MYSQL_INT),
            'details' => new DataProperty('txtDetails', null, DataProperty::MYSQL_STRING),
            'value' => new DataProperty('numValue', 0, DataProperty::MYSQL_DOUBLE),
            'eventId' => new DataProperty('numEventId', null, DataProperty::MYSQL_INT),
            'professorWorkSheetId' => new DataProperty('numProfWorkSheetId', null, DataProperty::MYSQL_INT)
        ];

        $this->properties->value->valueTransformer = function($value)
        {
            if (!isset($this->otherProperties->radEntryType)) return $value;
            return $this->otherProperties->radEntryType == 1 ? -abs($value) : abs($value);
        };
    }

    protected string $databaseTable = 'budgetentries';
    protected string $formFieldPrefixName = 'budgetentries';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getCount(mysqli $conn, int $year, string $searchKeywords, ?string $fromValue, ?string $toValue, ?string $fromDate, ?string $toDate) : int
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable)
        ->addWhereClause("date >= CONCAT(?, '-01-01') AND date <= CONCAT(?, '-12-31') ")
        ->addValues('ss', [ $year, $year]);   
        
        $selector = $this->mutateSqlSelectorForSearchParameters($selector, $searchKeywords, $fromValue, $toValue, $fromDate, $toDate);

        return (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    } 

    public function getMultiplePartially(mysqli $conn, int $year, $page, $numResultsOnPage, $_orderBy, $searchKeywords, ?string $fromValue, ?string $toValue, ?string $fromDate, ?string $toDate) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn($this->databaseTable. '.*')
        ->addSelectColumn("ABS({$this->databaseTable}.value) AS absValue ")
        ->addSelectColumn('enums.value AS categoryName ')
        ->addSelectColumn('enums.type AS enumType ')
        ->setTable($this->databaseTable)
        ->addJoin("LEFT JOIN enums ON enums.type = 'BUDGETCAT' AND enums.id = {$this->databaseTable}.category ")
        ->addWhereClause("date >= CONCAT(?, '-01-01') AND date <= CONCAT(?, '-12-31') ")
        ->addValues('ss', [ $year, $year ]);

	    $calc_page = ($page - 1) * $numResultsOnPage;
        $selector = $this->mutateSqlSelectorForSearchParameters($selector, $searchKeywords, $fromValue, $toValue, $fromDate, $toDate)
        ->setLimit(' ?,? ')
        ->addValues('ii', [$calc_page, $numResultsOnPage]);

        switch ($_orderBy)
        {
            case 'id':
                $selector = $selector->setOrderBy($this->databaseTable . '.id ASC ');
                break;
            case 'dateAsc':
                $selector = $selector->setOrderBy($this->databaseTable . '.date ASC ');
                break;
            case 'dateDesc':
                $selector = $selector->setOrderBy($this->databaseTable . '.date DESC ');
                break;
            case 'valueAsc':
                $selector = $selector->setOrderBy('absValue ASC ');
                break;
            case 'valueDesc':
                $selector = $selector->setOrderBy('absValue DESC ');
                break;
        }

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    private function mutateSqlSelectorForSearchParameters(SqlSelector &$sel, string $searchKeywords, ?float $fromValue, ?float $toValue, ?string $fromDate, ?string $toDate) : SqlSelector
    {
        $textFieldMode = null; 
        $selector = $sel;
        
        if (mb_strlen($searchKeywords) > 3)
        {
            $textFieldMode = 'text';
            if (isSearchById($searchKeywords))
                $textFieldMode = 'id';
        }

        switch ($textFieldMode)
        {
            case 'text':
                $selector = $selector
                ->addWhereClause('AND (MATCH (details) AGAINST (?)) ')
                ->addValue('s', $searchKeywords);
                break;
            case 'id':
                $searchById = new \SearchById($searchKeywords, $this->databaseTable . '.id');
                $whereSearch = "AND (" . $searchById->generateSQLWhereConditions() . ") ";
                $selector = $selector
                ->addWhereClause($whereSearch)
                ->addValues($searchById->generateBindParamTypes(), $searchById->generateBindParamValues());
                break;
            default: break;
        }

        if (isset($fromValue) && is_numeric($fromValue))
        {
            $selector = $selector
            ->addWhereClause("AND ABS({$this->databaseTable}.value) >= ? ")
            ->addValue('d', $fromValue);
        }

        if (isset($toValue) && is_numeric($fromValue))
        {
            $selector = $selector
            ->addWhereClause("AND ABS({$this->databaseTable}.value) <= ? ")
            ->addValue('d', $toValue);
        }

        if (!empty($fromDate))
        {
            //$from = DateTime::createFromFormat('d/m/Y', $fromDate);
            $selector = $selector
            ->addWhereClause("AND {$this->databaseTable}.date >= ? ")
            ->addValue('s', $fromDate);
        }

        if (!empty($toDate))
        {
            //$to = DateTime::createFromFormat('d/m/Y', $toDate);
            $selector = $selector
            ->addWhereClause("AND {$this->databaseTable}.date <= ? ")
            ->addValue('s', $toDate);
        }

        return $selector;
    }
}