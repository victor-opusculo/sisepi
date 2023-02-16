<?php

namespace Model\Events;

use DataEntity;
use DataProperty;
use SqlSelector;
use mysqli;

require_once __DIR__ . '/../DataEntity.php';

class EventCertificate extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'eventId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'dateTime' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'email' => new DataProperty('', null, DataProperty::MYSQL_STRING, true)
        ];
    }

    protected string $databaseTable = 'certificates';
    protected string $formFieldPrefixName = '';
    protected array $primaryKeys = ['id'];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new EventCertificate();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new; 
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $_orderBy, $searchKeywords) : array
    {
        $conn->query('SET SQL_BIG_SELECTS = 1;');

        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName("id"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("eventId"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("dateTime"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("email"));
        $selector->addSelectColumn("events.name AS eventName");
        $selector->addSelectColumn("
        (CASE
        WHEN events.subscriptionListNeeded = 1 THEN
            CONVERT(aes_decrypt(subscriptionstudentsnew.name, '{$this->encryptionKey}') using 'utf8mb4')
        WHEN events.subscriptionListNeeded = 0 THEN
            CONVERT(aes_decrypt(presencerecords.name, '{$this->encryptionKey}') using 'utf8mb4')
        END) AS name ");

        $selector->setTable($this->databaseTable);

        $selector->addJoin("LEFT JOIN events ON events.id = {$this->databaseTable}.eventId ");
        $selector->addJoin("LEFT JOIN subscriptionstudentsnew ON subscriptionstudentsnew.email = {$this->databaseTable}.email ");
        $selector->addJoin("LEFT JOIN presencerecords ON presencerecords.email = {$this->databaseTable}.email ");

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" 
            (CASE 
                WHEN events.subscriptionListNeeded = 1 THEN
                   convert(aes_decrypt(subscriptionstudentsnew.name, '{$this->encryptionKey}') using 'utf8mb4') like ?
                WHEN events.subscriptionListNeeded = 0 THEN
                  convert(aes_decrypt(presencerecords.name, '{$this->encryptionKey}') using 'utf8mb4') like ?
            END) ");
            $selector->addWhereClause(" OR CONVERT(" . $this->getWhereQueryColumnName("email") . " USING 'utf8mb4') LIKE ? ");
            $keywordsParam = "%" . $searchKeywords . "%";
            $selector->addValues('sss', [ $keywordsParam, $keywordsParam, $keywordsParam ]);
        }

        switch ($_orderBy)
        {
            case 'name':
                $selector->setOrderBy('name ASC ');
                break;
            case 'email':
                $selector->setOrderBy('email ASC ');
                break;
            case 'date':
            default:
                $selector->setOrderBy('dateTime DESC ');
                break;
        }

        $selector->setGroupBy(' certificates.id ');

		$calc_page = ($page - 1) * $numResultsOnPage;
        $selector->setLimit(' ?,? ');
        $selector->addValues('ii', [ $calc_page, $numResultsOnPage ]);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);

        return $output;
    }

    public function getCount(mysqli $conn, $searchKeywords)
    {
        $conn->query('SET SQL_BIG_SELECTS = 1;');

        $selector = new SqlSelector();
        $selector->addSelectColumn('count(*)');

        $selector->setTable($this->databaseTable);

        $selector->addJoin("LEFT JOIN events ON events.id = {$this->databaseTable}.eventId ");
        $selector->addJoin("LEFT JOIN subscriptionstudentsnew ON subscriptionstudentsnew.email = {$this->databaseTable}.email ");
        $selector->addJoin("LEFT JOIN presencerecords ON presencerecords.email = {$this->databaseTable}.email ");
        
        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" 
            (CASE 
                WHEN events.subscriptionListNeeded = 1 THEN
                   convert(aes_decrypt(subscriptionstudentsnew.name, '{$this->encryptionKey}') using 'utf8mb4') like ?
                WHEN events.subscriptionListNeeded = 0 THEN
                  convert(aes_decrypt(presencerecords.name, '{$this->encryptionKey}') using 'utf8mb4') like ?
            END) ");
            $selector->addWhereClause(" OR CONVERT(" . $this->getWhereQueryColumnName("email") . " USING 'utf8mb4') LIKE ? ");
            $keywordsParam = "%" . $searchKeywords . "%";
            $selector->addValues('sss', [ $keywordsParam, $keywordsParam, $keywordsParam ]);
        }

        $selector->setGroupBy(' certificates.id ');

        return $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function getAllForExport(mysqli $conn, $_orderBy, $searchKeywords)
    {
        $conn->query('SET SQL_BIG_SELECTS = 1;');

        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName("id"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("eventId"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("dateTime"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("email"));
        $selector->addSelectColumn("events.name AS eventName");
        $selector->addSelectColumn("
        (CASE
        WHEN events.subscriptionListNeeded = 1 THEN
            CONVERT(aes_decrypt(subscriptionstudentsnew.name, '{$this->encryptionKey}') using 'utf8mb4')
        WHEN events.subscriptionListNeeded = 0 THEN
            CONVERT(aes_decrypt(presencerecords.name, '{$this->encryptionKey}') using 'utf8mb4')
        END) AS name ");

        $selector->setTable($this->databaseTable);

        $selector->addJoin("LEFT JOIN events ON events.id = {$this->databaseTable}.eventId ");
        $selector->addJoin("LEFT JOIN subscriptionstudentsnew ON subscriptionstudentsnew.email = {$this->databaseTable}.email ");
        $selector->addJoin("LEFT JOIN presencerecords ON presencerecords.email = {$this->databaseTable}.email ");

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" 
            (CASE 
                WHEN events.subscriptionListNeeded = 1 THEN
                   convert(aes_decrypt(subscriptionstudentsnew.name, '{$this->encryptionKey}') using 'utf8mb4') like ?
                WHEN events.subscriptionListNeeded = 0 THEN
                  convert(aes_decrypt(presencerecords.name, '{$this->encryptionKey}') using 'utf8mb4') like ?
            END) ");
            $selector->addWhereClause(" OR CONVERT(" . $this->getWhereQueryColumnName("email") . " USING 'utf8mb4') LIKE ? ");
            $keywordsParam = "%" . $searchKeywords . "%";
            $selector->addValues('sss', [ $keywordsParam, $keywordsParam, $keywordsParam ]);
        }

        switch ($_orderBy)
        {
            case 'name':
                $selector->setOrderBy('name ASC ');
                break;
            case 'email':
                $selector->setOrderBy('email ASC ');
                break;
            case 'date':
            default:
                $selector->setOrderBy('dateTime DESC ');
                break;
        }

        $selector->setGroupBy(' certificates.id ');

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);

        return $output;
    }

    public function toArrayWithPtBrHeaders() : array
    {
        return
        [
            'ID' => $this->properties->id->getValue(),
            'Evento ID' => $this->properties->eventId->getValue(),
            'Nome' => $this->otherProperties->name ?? '',
            'E-mail' => $this->properties->email->getValue(),
            'Evento' => $this->otherProperties->eventName ?? '',
            'Data de emissão' => $this->properties->dateTime->getValue()
        ];
    }
}