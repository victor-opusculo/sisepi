<?php

namespace Model\Events;

use DataEntity;
use DataProperty;
use Exception;
use mysqli;
use mysqli_driver;
use SqlSelector;

require_once __DIR__ . '/../DataEntity.php';

class EventSubscription extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('eventSubscriptionId', null, DataProperty::MYSQL_INT, false),
            'eventId' => new DataProperty('eventId', null, DataProperty::MYSQL_INT, false),
            'name' => new DataProperty('txtName', null, DataProperty::MYSQL_STRING, true),
            'email' => new DataProperty('txtEmail', null, DataProperty::MYSQL_STRING, true),
            'subscriptionDataJson' => new DataProperty('', null, DataProperty::MYSQL_STRING, true),
            'subscriptionDate' => new DataProperty('', null, DataProperty::MYSQL_STRING, false)
        ];
    }

    protected string $databaseTable = 'subscriptionstudentsnew';
    protected string $formFieldPrefixName = 'subscriptionstudents';
    protected array $primaryKeys = ['id'];

    private ?object $studentDataObject; 

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new EventSubscription();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new; 
    }

    public function fillPropertiesFromDataRow($dataRow)
    {
        parent::fillPropertiesFromDataRow($dataRow);
        $this->studentDataObject = json_decode($this->subscriptionDataJson);
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $_orderBy, $searchKeywords) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName("id"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("eventId"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("name"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("email"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("subscriptionDataJson"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("subscriptionDate"));
        $selector->addSelectColumn("events.name AS eventName");

        $selector->setTable($this->databaseTable);

        $selector->addJoin("INNER JOIN events ON events.id = {$this->databaseTable}.eventId ");

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" CONVERT(" . $this->getWhereQueryColumnName("name") . " USING 'utf8mb4') LIKE ? ");
            $selector->addWhereClause(" OR CONVERT(" . $this->getWhereQueryColumnName("email") . " USING 'utf8mb4') LIKE ? ");
            $keywordsParam = "%" . $searchKeywords . "%";
            $selector->addValues('ss', [ $keywordsParam, $keywordsParam ]);
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
                $selector->setOrderBy('subscriptionDate DESC ');
                break;
        }

		$calc_page = ($page - 1) * $numResultsOnPage;
        $selector->setLimit(' ?,? ');
        $selector->addValues('ii', [ $calc_page, $numResultsOnPage ]);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);

        return $output;
    }

    public function getCount(mysqli $conn, $searchKeywords)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('count(*)');

        $selector->setTable($this->databaseTable);
        
        if (mb_strlen($searchKeywords) > 3)
        {
		    $selector->addWhereClause(" CONVERT(" . $this->getWhereQueryColumnName("name") . " USING 'utf8mb4') LIKE ? ");
            $selector->addWhereClause(" OR CONVERT(" . $this->getWhereQueryColumnName("email") . " USING 'utf8mb4') LIKE ? ");
            $keywordsParam = "%" . $searchKeywords . "%";
            $selector->addValues('ss', [ $keywordsParam, $keywordsParam ]);
        }

        return $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function getAllForExport(mysqli $conn, $_orderBy, $searchKeywords) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->getSelectQueryColumnName("id"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("eventId"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("name"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("email"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("subscriptionDataJson"));
        $selector->addSelectColumn($this->getSelectQueryColumnName("subscriptionDate"));
        $selector->addSelectColumn("events.name AS eventName");

        $selector->setTable($this->databaseTable);

        $selector->addJoin("INNER JOIN events ON events.id = {$this->databaseTable}.eventId ");

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector->addWhereClause(" CONVERT(" . $this->getWhereQueryColumnName("name") . " USING 'utf8mb4') LIKE ? ");
            $selector->addWhereClause(" OR CONVERT(" . $this->getWhereQueryColumnName("email") . " USING 'utf8mb4') LIKE ? ");
            $keywordsParam = "%" . $searchKeywords . "%";
            $selector->addValues('ss', [ $keywordsParam, $keywordsParam ]);
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
                $selector->setOrderBy('subscriptionDate DESC ');
                break;
        }

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = array_map( fn($dr) => $this->newInstanceFromDataRow($dr), $drs);

        return $output;
    }

    public function getQuestions()
    {
        return $this->studentDataObject->questions ?? [];
    }

    public function getTerms()
    {
        return $this->studentDataObject->terms  ?? [];
    }

    public function getStudentDataObject()
    {
        return $this->studentDataObject ?? null;
    }

    public function getQuestionAnswer($identifierName)
    {
        $questions = $this->getQuestions();
        $specificQuest = array_filter($questions, fn($q) => $q->identifier === $identifierName);
        $answer = array_pop($specificQuest)->value ?? null;
        return $answer;
    }

    public function getMultipleFromEvent(mysqli $conn) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn("{$this->databaseTable}.id");
        $selector->addSelectColumn("{$this->databaseTable}.eventId");
        $selector->addSelectColumn("aes_decrypt({$this->databaseTable}.name, '{$this->encryptionKey}') AS name ");
        $selector->addSelectColumn("aes_decrypt({$this->databaseTable}.email, '{$this->encryptionKey}') AS email ");
        $selector->addSelectColumn("aes_decrypt({$this->databaseTable}.subscriptionDataJson, '{$this->encryptionKey}') AS subscriptionDataJson ");
        $selector->addSelectColumn("{$this->databaseTable}.subscriptionDate");

        $selector->addSelectColumn("events.name AS eventName ");
        $selector->setTable($this->databaseTable);
        $selector->addJoin("INNER JOIN events ON events.id = {$this->databaseTable}.eventId ");
        $selector->addWhereClause("{$this->databaseTable}.eventId = ? ");
        $selector->addValue('i', $this->properties->eventId->getValue());

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);

        $output = [];
        foreach ($drs as $dr)
            $output[] = $this->newInstanceFromDataRow($dr);

        return $output;
    }

    public function getSubscriptionsHoursByQuestionValue(mysqli $conn, string $questionValue, string $beginDate, string $endDate) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('cmiw.name');
        $selector->addSelectColumn('cmiw.email');
        $selector->addSelectColumn('count(cmiw.eventId) as eventsSubscribled');
        $selector->addSelectColumn('sec_to_time(sum(time_to_sec(eh.hours))) as hours');
        
        $selector->setTable("(SELECT
		cast(aes_decrypt(subscriptionstudentsnew.name, '{$this->encryptionKey}') as char) as name,
		cast(aes_decrypt(subscriptionstudentsnew.email, '{$this->encryptionKey}') as char) as email,
		cast(aes_decrypt(subscriptionstudentsnew.subscriptionDataJson, '{$this->encryptionKey}') as char) as json,
		subscriptionstudentsnew.eventId,
		subscriptionstudentsnew.id as subscriptionId
		FROM subscriptionstudentsnew
		WHERE json_extract(cast(aes_decrypt(subscriptionstudentsnew.subscriptionDataJson, '{$this->encryptionKey}') as char), '$.questions[*].value') LIKE ?
		AND
		(SELECT
			(count(presencerecords.id)/(SELECT count(eventdates.id) FROM eventdates WHERE eventdates.eventId = subscriptionstudentsnew.eventId)) as presencePercent
			FROM presencerecords
			WHERE presencerecords.subscriptionId = subscriptionstudentsnew.id
		) >= (SELECT settings.value FROM settings WHERE settings.name = 'STUDENTS_MIN_PRESENCE_PERCENT') / 100
	) as cmiw");

    $selector->addJoin('JOIN 
	(SELECT
		events.id as eventId,
		sec_to_time(sum(time_to_sec(eventdates.endTime) - time_to_sec(eventdates.beginTime))) as hours,
     	min(eventdates.date) as eventBegin,
     	max(eventdates.date) as eventEnd
		FROM events
		INNER JOIN eventdates ON eventdates.eventId = events.id
		GROUP BY eventdates.eventId
	) as eh ON eh.eventId = cmiw.eventId');

    $selector->addWhereClause(" eh.eventBegin >= ? AND eh.eventEnd <= ? ");
    $selector->setGroupBy(" cmiw.name ");

    $selector->addValues('sss', [ '%' . $questionValue . '%', $beginDate, $endDate ]);

    $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);

    if (!empty($drs))
        return $drs;
    else
        throw new Exception('Nenhum dado encontrado para os critérios atuais de pesquisa.');
    }

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        $this->properties->subscriptionDate->setValue(date('Y-m-d H:i:s'));
        return 0;
    }
}