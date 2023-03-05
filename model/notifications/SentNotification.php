<?php

namespace Model\Notifications;

use DataEntity;
use DataProperty;
use mysqli;
use SqlSelector;

require_once __DIR__ . '/../DataEntity.php';

class SentNotification extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'userId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'title' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'description' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'iconFilePath' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'linkUrlInfos' => new DataProperty('', null, DataProperty::MYSQL_STRING),
            'isRead' => new DataProperty('', 0, DataProperty::MYSQL_INT),
            'dateTime' => new DataProperty('', date('Y-m-d H:i:s'), DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'sentnotifications';
    protected string $formFieldPrefixName = '';
    protected array $primaryKeys = [ 'id' ];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new SentNotification();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function getUnreadCount(mysqli $conn) : int
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("count(*)");
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause(" {$this->databaseTable}.userId = ? ");
        $selector->addWhereClause(" AND {$this->databaseTable}.isRead = 0 ");
        $selector->addValue('i', $this->properties->userId->getValue());

        return $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function getCount(mysqli $conn, string $searchKeywords) : int
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("count(*)");
        $selector->setTable($this->databaseTable);
        $selector->addWhereClause(" {$this->databaseTable}.userId = ? ");
        $selector->addValue('i', $this->properties->userId->getValue());

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" AND MATCH (title, description) AGAINST (?) ");
            $selector->addValue('s', $searchKeywords);
        }

        return $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $orderBy, $searchKeywords)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('*');
        $selector->setTable($this->databaseTable);

        $selector->addWhereClause(" {$this->databaseTable}.userId = ? ");
        $selector->addValue('i', $this->properties->userId->getValue());

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(' AND MATCH (title, description) AGAINST (?) ');
            $selector->addValue('s', $searchKeywords);
        }

        switch ($orderBy)
        {
            case 'title':
                $selector->setOrderBy('title ASC');
                break;
            case 'read':
                $selector->setOrderBy('isRead ASC ');
                break;
            case 'date':
            default:
                $selector->setOrderBy('dateTime DESC');
                break;
        }

        $calc_page = ($page - 1) * $numResultsOnPage;
        $selector->setLimit(' ?,? ');
        $selector->addValues('ii', [ $calc_page, $numResultsOnPage ]);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);
    }
}