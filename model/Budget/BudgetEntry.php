<?php
namespace SisEpi\Model\Budget;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../../includes/SearchById.php";

use Exception;
use mysqli;
use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\Exceptions\DatabaseEntityNotFound;
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

    public function getSingle(mysqli $conn)
    {
        $selector = $this->getGetSingleSqlSelector()
        ->addSelectColumn("ABS({$this->databaseTable}.value) AS absValue")
        ->addSelectColumn('enums.value AS categoryName ')
        ->addSelectColumn(" events.name AS eventName ")
        ->addSelectColumn(" JSON_EXTRACT(professorworksheets.participationEventDataJson, '$.activityName') AS profWorkSheetActivityName ")
        ->addJoin("LEFT JOIN enums ON enums.type = 'BUDGETCAT' AND enums.id = {$this->databaseTable}.category ")
        ->addJoin("LEFT JOIN events ON events.id = {$this->databaseTable}.eventId ")
        ->addJoin("LEFT JOIN professorworksheets ON professorworksheets.id = {$this->databaseTable}.professorWorkSheetId ");

        $dataRow = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        if (isset($dataRow))
            return $this->newInstanceFromDataRow($dataRow);
        else
			throw new \SisEpi\Model\Exceptions\DatabaseEntityNotFound('Dotação não localizada!', $this->databaseTable);
    }

    public function getCount(mysqli $conn, int $year, string $searchKeywords, ?string $fromValue, ?string $toValue, ?string $fromDate, ?string $toDate) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*) AS count')
        ->addSelectColumn("SUM({$this->databaseTable}.value) AS sumValue")
        ->setTable($this->databaseTable)
        ->addWhereClause("date >= CONCAT(?, '-01-01') AND date <= CONCAT(?, '-12-31') ")
        ->addValues('ss', [ $year, $year]);   
        
        $selector = $this->mutateSqlSelectorForSearchParameters($selector, $searchKeywords, $fromValue, $toValue, $fromDate, $toDate);

        return $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
    } 

    public function getMultiplePartially(mysqli $conn, int $year, $page, $numResultsOnPage, $_orderBy, $searchKeywords, ?string $fromValue, ?string $toValue, ?string $fromDate, ?string $toDate) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn($this->databaseTable. '.*')
        ->addSelectColumn("ABS({$this->databaseTable}.value) AS absValue ")
        ->addSelectColumn(" events.name AS eventName ")
        ->addSelectColumn('enums.value AS categoryName ')
        ->addSelectColumn('enums.type AS enumType ')
        ->setTable($this->databaseTable)
        ->addJoin("LEFT JOIN enums ON enums.type = 'BUDGETCAT' AND enums.id = {$this->databaseTable}.category ")
        ->addJoin("LEFT JOIN events ON events.id = {$this->databaseTable}.eventId ")
        ->addWhereClause("date >= CONCAT(?, '-01-01') AND date <= CONCAT(?, '-12-31') ")
        ->addValues('ss', [ $year, $year ]);

	    $calc_page = ($page - 1) * $numResultsOnPage;
        $selector = $this->mutateSqlSelectorForSearchParameters($selector, $searchKeywords, $fromValue, $toValue, $fromDate, $toDate)
        ->setLimit(' ?,? ')
        ->addValues('ii', [$calc_page, $numResultsOnPage]);

        switch ($_orderBy)
        {
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
            case 'id':
            default:
                $selector = $selector->setOrderBy($this->databaseTable . '.id ASC ');
                break;
        }

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function getAllFromEvent(mysqli $conn) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn("{$this->databaseTable}.*")
        ->addSelectColumn("ABS({$this->databaseTable}.value) AS absValue")
        ->addSelectColumn('enums.value AS categoryName ')
        ->addSelectColumn(" events.name AS eventName ")
        ->addSelectColumn(" JSON_EXTRACT(professorworksheets.participationEventDataJson, '$.activityName') AS profWorkSheetActivityName ")
        ->addJoin("LEFT JOIN enums ON enums.type = 'BUDGETCAT' AND enums.id = {$this->databaseTable}.category ")
        ->addJoin("LEFT JOIN events ON events.id = {$this->databaseTable}.eventId ")
        ->addJoin("LEFT JOIN professorworksheets ON professorworksheets.id = {$this->databaseTable}.professorWorkSheetId ")
        ->setTable($this->databaseTable)
        ->addWhereClause(" {$this->databaseTable}.eventId = ? ")
        ->addValue('i', $this->properties->eventId->getValue());

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        $this->checkEventAndWorkSheetIds($conn);
        return 0;
    }

    public function beforeDatabaseUpdate(mysqli $conn): int
    {
        $this->checkEventAndWorkSheetIds($conn);
        return 0;
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

    private function checkEventAndWorkSheetIds(mysqli $conn)
    {
        try
        {
            if (!empty($this->properties->eventId->getValue()))
            {
                $eventGetter = new \SisEpi\Model\Events\Event();
                $eventGetter->id = $this->properties->eventId->getValue();
                $eventGetter->getSingle($conn);
            }

            if (!empty($this->properties->professorWorkSheetId->getValue()))
            {
                $workSheetGetter = new \SisEpi\Model\Professors\ProfessorWorkSheet();
                $workSheetGetter->id = $this->properties->professorWorkSheetId->getValue();
                $workSheetGetter->getSingle($conn);
            }
        }
        catch (\SisEpi\Model\Exceptions\DatabaseEntityNotFound $e)
        {
            throw new Exception("Erro: ID de evento ou ficha de trabalho não localizados.");
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }
}