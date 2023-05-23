<?php
//Public

namespace SisEpi\Pub\Model\Events;

use Illuminate\Support\Arr;
use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use mysqli;
use SisEpi\Model\SqlSelector;
use SisEpi\Model\Ods\OdsRelation;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../model/exceptions.php';
require_once __DIR__ . '/../Database/generalsettings.database.php';

class Event extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('eventId', null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('txtEventName', null, DataProperty::MYSQL_STRING),
            'typeId' => new DataProperty('cmbEventType', null, DataProperty::MYSQL_INT),
            'subscriptionListNeeded' => new DataProperty('chkSubscriptionListNeeded', 0, DataProperty::MYSQL_INT),
            'subscriptionListOpeningDate' => new DataProperty('dateSubscriptionListOpeningDate', null, DataProperty::MYSQL_STRING),
            'subscriptionListClosureDate' => new DataProperty('dateSubscriptionListClosureDate', null, DataProperty::MYSQL_STRING),
            'maxSubscriptionNumber' => new DataProperty('txtMaxSubscriptionNumber', 80, DataProperty::MYSQL_INT),
            'allowLateSubscriptions' => new DataProperty('chkAllowLateSubscriptions', 0, DataProperty::MYSQL_INT),
            'posterImageAttachmentFileName' => new DataProperty('radAttachmentPosterImage', null, DataProperty::MYSQL_STRING),
            'responsibleForTheEvent' => new DataProperty('txtResponsibleForTheEvent', null, DataProperty::MYSQL_STRING),
            'customInfosJson' => new DataProperty('hidCustomInfos', '[]', DataProperty::MYSQL_STRING),
            'moreInfos' => new DataProperty('txtMoreInfos', null, DataProperty::MYSQL_STRING),
            'certificateText' => new DataProperty('txtCertificateText', null, DataProperty::MYSQL_STRING),
            'certificateBgFile' => new DataProperty('txtCertificateBgFile', readSetting("STUDENTS_CURRENT_CERTIFICATE_BG_FILE"), DataProperty::MYSQL_STRING),
            'checklistId' => new DataProperty('', null, DataProperty::MYSQL_INT),
            'surveyTemplateId' => new DataProperty('selSurveyTemplate', null, DataProperty::MYSQL_INT),
            'testTemplateId' => new DataProperty('numTestTemplate', null, DataProperty::MYSQL_INT),
            'subscriptionTemplateId' => new DataProperty('txtSubscriptionTemplate', null, DataProperty::MYSQL_INT)
        ];

        $this->properties->certificateText->valueTransformer = function($val)
        {
             $enabled = $this->otherProperties->chkAutoCertificate ?? true;
             return $enabled ? $val : null;
        };

        $this->properties->surveyTemplateId->valueTransformer = function($val)
        {
            $enabled = $this->otherProperties->chkEnableSurvey ?? true;
            return $enabled ? $val : null;
        };

        $this->properties->testTemplateId->valueTransformer = function($val)
        {
            $enabled = $this->otherProperties->chkEnableTest ?? true;
            return $enabled ? $val : null;
        };
    }

    protected string $databaseTable = 'events';
    protected string $formFieldPrefixName = 'events';
    protected array $primaryKeys = ['id'];

    public array $eventDates = [];
    public array $eventAttachments = [];
    public ?OdsRelation $odsRelation = null;

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new Event();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function fillPropertiesWithDefaultValues()
    {
        parent::fillPropertiesWithDefaultValues();
    }

    public function getSingle(mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->databaseTable . '.*');
        $selector->addSelectColumn('enums.value AS typeName');
        $selector->addSelectColumn('MIN(eventdates.date) AS beginDate');
        $selector->addSelectColumn('MAX(eventdates.date) AS endDate');
        $selector->addSelectColumn("JSON_EXTRACT(jsontemplates.templateJson, '$.classTimeHours') AS 'testHours'");
        $selector->addSelectColumn("SEC_TO_TIME( SUM( TIME_TO_SEC( TIMEDIFF( eventdates.endTime, eventdates.beginTime ) ) ) ) AS 'hours'");
        $selector->addSelectColumn("(select group_concat(COALESCE(eventlocations.type, 'null')) from eventdates left join eventlocations on eventlocations.id = eventdates.locationId where eventdates.eventId = events.id) as locTypes");
        
        $selector->setTable("events");
        
        $selector->addJoin("INNER JOIN eventdates ON eventdates.eventId = {$this->databaseTable}.id ");
        $selector->addJoin("LEFT JOIN jsontemplates ON jsontemplates.type = 'eventstudenttest' AND jsontemplates.id = {$this->databaseTable}.testTemplateId ");
        $selector->addJoin("right join enums on enums.type = 'EVENT' and enums.id = events.typeId ");
        $selector->addWhereClause("{$this->databaseTable}.id = ?");
        $selector->addValue('i', $this->id);
        
        $dataRow = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dataRow))
            return $this->newInstanceFromDataRow($dataRow);
        else
            throw new \SisEpi\Model\Exceptions\DatabaseEntityNotFound('Evento não localizado!', $this->databaseTable);
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $_orderBy, $searchKeywords) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('events.id ');
        $selector->addSelectColumn('events.name ');
        $selector->addSelectColumn('MIN(eventdates.date) AS date ');
        $selector->addSelectColumn("JSON_EXTRACT(jsontemplates.templateJson, '$.classTimeHours') AS 'testHours'");
        $selector->addSelectColumn("SEC_TO_TIME( SUM( TIME_TO_SEC( TIMEDIFF( endTime, beginTime ) ) ) ) AS 'hours'");
        $selector->addSelectColumn('enums.value AS typeName ');
        $selector->addSelectColumn("(SELECT group_concat(COALESCE(eventlocations.type, 'null')) from eventdates left join eventlocations on eventlocations.id = eventdates.locationId where eventdates.eventId = events.id) as locTypes ");

        $selector->setTable($this->databaseTable);

        $selector->addJoin('INNER JOIN eventdates ON eventdates.eventId = events.id ');
        $selector->addJoin("inner JOIN enums on enums.type = 'EVENT' and enums.id = events.typeId ");
        $selector->addJoin("LEFT JOIN jsontemplates ON jsontemplates.type = 'eventstudenttest' AND jsontemplates.id = {$this->databaseTable}.testTemplateId ");

        if (mb_strlen($searchKeywords) > 3)
        {
		    $querySearch = " match (events.name, events.moreInfos) against (?) ";
            $selector->addWhereClause($querySearch);
            $selector->addValue('s', $searchKeywords);
        }

        $selector->setGroupBy("{$this->databaseTable}.id, {$this->databaseTable}.name ");

        switch ($_orderBy)
        {
            case 'name':
                $selector->setOrderBy('events.name ASC ');
                break;
            case 'date':
            default:
                $selector->setOrderBy('date DESC ');
                break;
        }

		$calc_page = ($page - 1) * $numResultsOnPage;
        $selector->setLimit(' ?,? ');
        $selector->addValues('ii', [ $calc_page, $numResultsOnPage ]);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        $output = [];
        foreach ($drs as $dr)
        {
            $new = new Event();
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
        
        if (mb_strlen($searchKeywords) > 3)
        {
		    $querySearch = " match (events.name, events.moreInfos) against (?) ";
            $selector->addWhereClause($querySearch);
            $selector->addValue('s', $searchKeywords);
        }

        return $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function getTypes(mysqli $conn)
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('id');
        $selector->addSelectColumn('value AS name');
        $selector->setTable('enums');
        $selector->addWhereClause("type = 'EVENT' ");
        return $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
    }

    public function fetchSubEntities(mysqli $conn, $recursive = false)
    {
        $stmt = $conn->prepare('SELECT id FROM eventdates WHERE eventId = ? ORDER BY date ASC ');
        $evId = $this->properties->id->getValue();
        $stmt->bind_param('i', $evId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $getter = new \SisEpi\Pub\Model\Events\EventDate();
        while ($edId = $result->fetch_row())
        {
            $getter->id = $edId[0];
            $eventDate = $getter->getSingle($conn);

            if ($recursive)
            {
                $eventDate->setCryptKey($this->encryptionKey);
                $eventDate->fetchSubEntities($conn);
            }

            $this->eventDates[] = $eventDate;
        }

        $stmt = $conn->prepare('SELECT id FROM eventattachments WHERE eventId = ? ');
        $stmt->bind_param('i', $evId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $getter = new \SisEpi\Pub\Model\Events\EventAttachment();
        while ($eaId = $result->fetch_row())
        {
            $getter->id = $eaId[0];
            $eventAttachment = $getter->getSingle($conn);
            $this->eventAttachments[] = $eventAttachment;
        }

        $getter = new OdsRelation();
        $getter->eventId = $this->id;
        try
        {
            $this->odsRelation = $getter->getFromEvent($conn);
            $this->odsRelation->fetchOdsAndGoalsStructured($conn);
        }
        catch (\Exception $e) {}
    }

    public function fillPropertiesFromFormInput($post, $files = null)
    { }

    public function save(mysqli $conn)
    { }

    public function delete(mysqli $conn)
    { }
}