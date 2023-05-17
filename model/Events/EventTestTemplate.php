<?php
namespace SisEpi\Model\Events;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\SqlSelector;
use mysqli;

require_once __DIR__ . "/../../vendor/autoload.php";

class EventTestTemplate extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'type' => new DataProperty('hidTemplateType', 'eventstudenttest', DataProperty::MYSQL_STRING),
            'id' => new DataProperty('hidTestTemplateId', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('txtTestName', "Novo modelo de avaliação", DataProperty::MYSQL_STRING),
            'templateJson' => new DataProperty('hidTestDataJson', self::TEMPLATE_DEFAULT_JSON, DataProperty::MYSQL_STRING)
        ];
    }

    public const TEMPLATE_DEFAULT_JSON = 
    '{
        "title": "Avaliação",
        "percentForApproval": 70,
        "randomizeQuestions": true,
        "classTimeHours": 6,
        "questions": []
    }';

    protected string $databaseTable = 'jsontemplates';
    protected string $formFieldPrefixName = 'eventtesttemplate';
    protected array $primaryKeys = [ 'type', 'id' ];
    protected array $setPrimaryKeysValue = ['type'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getCount(mysqli $conn, string $searchKeywords) : int
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable)
        ->addWhereClause("type = 'eventstudenttest'");

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector = $selector
            ->addWhereClause(" MATCH (" . $this->getWhereQueryColumnName('name') . ") AGAINST (?) ")
            ->addValue('s', $searchKeywords);
        }

        return (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $_orderBy, $searchKeywords) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn($this->getSelectQueryColumnName('id'))
        ->addSelectColumn($this->getSelectQueryColumnName('name'))
        ->addSelectColumn($this->getSelectQueryColumnName('templateJson'))
        ->setTable($this->databaseTable)
        ->addWhereClause($this->getWhereQueryColumnName('type') . " = 'eventstudenttest'");

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector = $selector
            ->addWhereClause(" MATCH (" . $this->getWhereQueryColumnName('name') . ") AGAINST (?) ")
            ->addValue('s', $searchKeywords);
        }

        switch ($_orderBy)
        {
            case 'name': $selector = $selector->setOrderBy('name ASC'); break;
            case 'id': default: $selector = $selector->setOrderBy('id ASC'); break;
        }

        $calc_page = ($page - 1) * $numResultsOnPage;
        $selector = $selector
        ->setLimit(' ?, ? ')
        ->addValues('ii', [ $calc_page, $numResultsOnPage ]);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([$this, 'newInstanceFromDataRow'], $drs);
    }
}