<?php
//Public
namespace Model\LibraryCollection;

require_once __DIR__ . '/../database/librarycollection.database.php';
require_once __DIR__ . '/../../../model/DataEntity.php';
require_once __DIR__ . '/../../../includes/SearchById.php';
require_once __DIR__ . '/../../../model/exceptions.php';

use DataEntity;
use DataProperty;
use mysqli;
use SqlSelector;

class Publication extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('publicationId', null, DataProperty::MYSQL_INT),
            'registrationDate' => new DataProperty('dateRegistrationDate', null, DataProperty::MYSQL_STRING),
            'author' => new DataProperty('txtAuthor', null, DataProperty::MYSQL_STRING),
            'title' => new DataProperty('txtTitle', null, DataProperty::MYSQL_STRING),
            'cdu' => new DataProperty('txtCDU', null, DataProperty::MYSQL_STRING),
            'cdd' => new DataProperty('txtCDD', null, DataProperty::MYSQL_STRING),
            'isbn' => new DataProperty('txtISBN', null, DataProperty::MYSQL_STRING),
            'local' => new DataProperty('txtLocal', null, DataProperty::MYSQL_STRING),
            'publisher_edition' => new DataProperty('txtPublisher', null, DataProperty::MYSQL_STRING),
            'number' => new DataProperty('txtNumber', null, DataProperty::MYSQL_STRING),
            'month' => new DataProperty('txtMonth', null, DataProperty::MYSQL_STRING),
            'year' => new DataProperty('txtYear', null, DataProperty::MYSQL_STRING),
            'edition' => new DataProperty('txtEdition', null, DataProperty::MYSQL_STRING),
            'volume' => new DataProperty('txtVolume', null, DataProperty::MYSQL_STRING),
            'copyNumber' => new DataProperty('txtCopyNumber', null, DataProperty::MYSQL_STRING),
            'pageNumber' => new DataProperty('txtPageNumber', null, DataProperty::MYSQL_STRING),
            'typeAcquisitionId' => new DataProperty('selAcquisitionType', null, DataProperty::MYSQL_INT),
            'price' => new DataProperty('numPrice', null, DataProperty::MYSQL_DOUBLE),
            'prohibitedSale' => new DataProperty('chkProhibitedSale', 0, DataProperty::MYSQL_INT),
            'provider' => new DataProperty('txtProvider', null, DataProperty::MYSQL_STRING),
            'exclusionInfoTerm' => new DataProperty('txtExclusionInfo', null, DataProperty::MYSQL_STRING),
            'registeredByUserId' => new DataProperty('hidRegisteredByUserId', null, DataProperty::MYSQL_INT)
        ];
    }

    protected string $databaseTable = 'librarycollection';
    protected string $formFieldPrefixName = 'libcollection';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new Publication();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getSingle(mysqli $conn) : Publication
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->databaseTable . '.*');
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause("{$this->databaseTable}.id = ?");
        $selector->addValue('i', $this->id);

        $dataRow = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
		if (isset($dataRow)) 
            return $this->newInstanceFromDataRow($dataRow);
        else
            throw new \Model\Exceptions\DatabaseEntityNotFound('Publicação não localizada!', $this->databaseTable);
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $_orderBy, $searchKeywords) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('id');
        $selector->addSelectColumn('title');
        $selector->addSelectColumn('author');
        $selector->addSelectColumn('cdu');
        $selector->addSelectColumn('cdd');
        $selector->addSelectColumn('isbn');

        $selector->setTable($this->databaseTable);
        
        $this->mutateSqlSelectorForSearchParameters($selector, $_orderBy, $searchKeywords);

	    $calc_page = ($page - 1) * $numResultsOnPage;
        $selector->setLimit(' ?,? ');
        $selector->addValue('i', $calc_page);
        $selector->addValue('i', $numResultsOnPage);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = [];
        foreach ($drs as $dr)
        {
            $new = new Publication();
            $new->fillPropertiesFromDataRow($dr);
            $output[] = $new;
        }

        return $output;
    }

    public function getCount(mysqli $conn, $searchKeywords)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('count(*)');

        $selector->setTable($this->databaseTable);
        
        $this->mutateSqlSelectorForSearchParameters($selector, null, $searchKeywords);

        return $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function isAvailableForBorrowing(mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("count(libraryborrowedpublications.id)");
        $selector->setTable('libraryborrowedpublications');
        $selector->addWhereClause('publicationId = ?');
        $selector->addWhereClause(' AND returnDatetime is null');
        $selector->addValue('i', $this->id);

        $borrowsCount = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);

        $selector2 = new SqlSelector();
        $selector2->addSelectColumn('exclusionInfoTerm');
        $selector2->setTable($this->databaseTable);
        $selector2->addWhereClause('id = ?');
        $selector2->addValue('i', $this->id);
        $exclusionInfo = $selector2->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);

        return (int)$borrowsCount === 0 && empty($exclusionInfo); 
    }

    public function getReservationsNumber(mysqli $conn)
    {
        require_once __DIR__ . '/../database/ext.libraryreservations.database.php';

        invalidatePendingAndOldReservations($this->id, $conn);

        $selector = new SqlSelector();
        $selector->addSelectColumn('COUNT(*)');
        $selector->setTable('libraryreservations');
        $selector->addWhereClause('publicationId = ?');
        $selector->addValue('i', $this->id);
        $selector->addWhereClause('AND borrowedPubId IS NULL');
        $selector->addWhereClause('AND invalidatedDatetime IS NULL');

        return $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    private function mutateSqlSelectorForSearchParameters(SqlSelector &$selector, $_orderBy, $searchKeywords)
    {
        $hasWhereClause = false;
        if (mb_strlen($searchKeywords) > 3 && !isSearchById($searchKeywords))
        {
            $whereSearch = " (MATCH (author, title, cdu, cdd, isbn, publisher_edition, provider) AGAINST (?)) ";
            $selector->addWhereClause($whereSearch);
            $selector->addValue('s', $searchKeywords);
            $hasWhereClause = true;
        }
        else if (isSearchById($searchKeywords))
        {
            $searchById = new \SearchById($searchKeywords, "id");
            $whereSearch = " (" . $searchById->generateSQLWhereConditions() . ") ";
            $selector->addWhereClause($whereSearch);
            $selector->addValues($searchById->generateBindParamTypes(), $searchById->generateBindParamValues());
            $hasWhereClause = true;
        }

        $selector->addWhereClause(($hasWhereClause ? 'AND ' : '') . "(exclusionInfoTerm IS NULL OR exclusionInfoTerm = '')");

        $orderBy = "";
        switch ($_orderBy)
        {
            case "title": $orderBy = " title ASC "; break;
            case "author": $orderBy = " author ASC "; break;
            case "id": default: $orderBy = " id ASC "; break;
        }
        $selector->setOrderBy($orderBy);
    }
}