<?php
namespace SisEpi\Model\LibraryCollection;

require_once __DIR__ . '/../Database/librarycollection.database.php';
require_once __DIR__ . '/../DataEntity.php';
require_once __DIR__ . '/../../includes/SearchById.php';
require_once __DIR__ . '/../exceptions.php';

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use mysqli;
use SisEpi\Model\SqlSelector;

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
            'authorCode' => new DataProperty('txtAuthorCode', null, DataProperty::MYSQL_STRING),
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
        $selector->addSelectColumn('acqtypeenum.value as acqTypeName');
        $selector->addSelectColumn('users.name as registeredByUserName');

        $selector->addJoin("left join enums acqtypeenum on acqtypeenum.type = 'LIBACQTYPE' and acqtypeenum.id = {$this->databaseTable}.typeAcquisitionId");
        $selector->addJoin("left join users on users.id = {$this->databaseTable}.registeredByUserId");
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause("{$this->databaseTable}.id = ?");
        $selector->addValue('i', $this->id);

        $dataRow = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
		if (isset($dataRow)) 
            return $this->newInstanceFromDataRow($dataRow);
        else
            throw new \SisEpi\Model\Exceptions\DatabaseEntityNotFound('Publicação não localizada!', $this->databaseTable);
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
        $selector->addSelectColumn('edition');
        $selector->addSelectColumn('volume');
        $selector->addSelectColumn('copyNumber');

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

    public function getLoanListLimited(mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('libraryborrowedpublications.*');
        $selector->addSelectColumn("aes_decrypt(libraryusers.name, '{$this->encryptionKey}') as userName");
        $selector->addSelectColumn('(returnDatetime is not null and returnDatetime <= now()) as isReturned');
        $selector->addSelectColumn('(returnDatetime is not null and returnDatetime > DATE_ADD(expectedReturnDatetime, INTERVAL 30 MINUTE)) as returnedLate');

        $selector->setTable('libraryborrowedpublications');
        $selector->addJoin('LEFT JOIN libraryusers ON libraryusers.id = libraryborrowedpublications.libUserId');
        $selector->addWhereClause('publicationId = ?');
        $selector->addValue('i', $this->id);
        $selector->setOrderBy('borrowDatetime DESC');
        $selector->setLimit('10');

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return $drs;
    }

    public function getReservationsListLimited(mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('libraryreservations.*');
        $selector->addSelectColumn("aes_decrypt(libraryusers.name, '{$this->encryptionKey}') as userName");
        $selector->addSelectColumn('(borrowedPubId is not null) as isFinalized');

        $selector->setTable('libraryreservations');
        $selector->addJoin('LEFT JOIN libraryusers ON libraryusers.id = libraryreservations.libUserId');
        $selector->addWhereClause('publicationId = ?');
        $selector->addValue('i', $this->id);
        $selector->setOrderBy('reservationDatetime DESC');
        $selector->setLimit('10');

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return $drs;
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

    public function getAllForExport(mysqli $conn, $_orderBy, $searchKeywords)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("{$this->databaseTable}.id as ID");
        $selector->addSelectColumn("registrationDate as 'Data de registro'");
        $selector->addSelectColumn("author as 'Autor'");
        $selector->addSelectColumn("title as 'Título'");
        $selector->addSelectColumn("cdu");
        $selector->addSelectColumn("cdd");
        $selector->addSelectColumn("isbn");
        $selector->addSelectColumn("authorCode as 'Cód. do autor'");
        $selector->addSelectColumn("local");
        $selector->addSelectColumn("publisher_edition as 'Editora'");
        $selector->addSelectColumn("number as 'Número'");
        $selector->addSelectColumn("month as 'Mês'");
        $selector->addSelectColumn("year as 'Ano'");
        $selector->addSelectColumn("edition as 'Edição'");
        $selector->addSelectColumn("volume as 'Volume'");
        $selector->addSelectColumn("copyNumber as 'Exemplar'");
        $selector->addSelectColumn("pageNumber as 'Número de páginas'");
        $selector->addSelectColumn("price as 'Preço'");
        $selector->addSelectColumn("prohibitedSale as 'Venda proibida'");
        $selector->addSelectColumn("provider as 'Fornecedor'");
        $selector->addSelectColumn("acqtypeenum.value as 'Tipo de aquisição'");
        $selector->addSelectColumn("exclusionInfoTerm as 'Exclusão'");
        $selector->addSelectColumn("users.name as 'Responsável pelo cadastro'");

        $selector->setTable($this->databaseTable);
        $selector->addJoin("LEFT join enums acqtypeenum on acqtypeenum.type = 'LIBACQTYPE' and acqtypeenum.id = {$this->databaseTable}.typeAcquisitionId");
        $selector->addJoin("LEFT join users on users.id = {$this->databaseTable}.registeredByUserId");
    
        $this->mutateSqlSelectorForSearchParameters($selector, $_orderBy, $searchKeywords);

        return $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
    }

    public function getAllForTags(mysqli $conn, $_orderBy, $searchKeywords)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('id');
        $selector->addSelectColumn('title');
        $selector->addSelectColumn('author');
        $selector->addSelectColumn('cdd');
        $selector->addSelectColumn('authorCode');
        $selector->addSelectColumn('volume');
        $selector->addSelectColumn('year');
        $selector->addSelectColumn('month');
        $selector->addSelectColumn('number');
        $selector->addSelectColumn('copyNumber');

        $selector->setTable($this->databaseTable);

        $this->mutateSqlSelectorForSearchParameters($selector, $_orderBy, $searchKeywords);

        return $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
    }

    private function mutateSqlSelectorForSearchParameters(SqlSelector &$selector, $_orderBy, $searchKeywords)
    {
        if (mb_strlen($searchKeywords) > 3 && !isSearchById($searchKeywords))
        {
            $whereSearch = " (MATCH (author, title, cdu, cdd, isbn, publisher_edition, provider, authorCode) AGAINST (?)) ";
            $selector->addWhereClause($whereSearch);
            $selector->addValue('s', $searchKeywords);
        }
        else if (isSearchById($searchKeywords))
        {
            $searchById = new \SearchById($searchKeywords, $this->databaseTable . ".id");
            $whereSearch = " (" . $searchById->generateSQLWhereConditions() . ") ";
            $selector->addWhereClause($whereSearch);
            $selector->addValues($searchById->generateBindParamTypes(), $searchById->generateBindParamValues());
        }

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