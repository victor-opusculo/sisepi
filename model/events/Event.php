<?php
namespace SisEpi\Model\Events;

use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\EntitiesChangesReport;
use SisEpi\Model\Events\EventWorkPlan;
use mysqli;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../DataEntity.php';
require_once __DIR__ . '/EventDate.php';
require_once __DIR__ . '/EventAttachment.php';
require_once __DIR__ . '/EventWorkPlan.php';
require_once __DIR__ . '/EventChecklist.php';
require_once __DIR__ . '/EventSurvey.php';
require_once __DIR__ . '/../EntitiesChangesReport.php';
require_once __DIR__ . '/../exceptions.php';
require_once __DIR__ . '/../database/events.uploadFiles.php';
require_once __DIR__ . '/../database/generalsettings.database.php';

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
            'subscriptionTemplateId' => new DataProperty('txtSubscriptionTemplate', null, DataProperty::MYSQL_INT)
        ];

        $this->properties->certificateText->valueTransformer = function($val)
        {
            if (isset($this->otherProperties->isEditMode))
                $enabled = $this->otherProperties->chkAutoCertificate ?? false;
            else
                $enabled = true;
            return $enabled ? $val : null;
        };

        $this->properties->surveyTemplateId->valueTransformer = function($val)
        {
            if (isset($this->otherProperties->isEditMode))
                $enabled = $this->otherProperties->chkEnableSurvey ?? false;
            else
                $enabled = true;
            return $enabled ? $val : null;
        };
    }

    protected string $databaseTable = 'events';
    protected string $formFieldPrefixName = 'events';
    protected array $primaryKeys = ['id'];

    protected ?EntitiesChangesReport $eventDatesChangesReport;
    protected ?EntitiesChangesReport $eventAttachmentsChangesReport;

    public array $eventDates = [];
    public array $eventAttachments = [];
    public array $eventSurveys = [];
    public ?EventWorkPlan $workPlan;
    public ?EventChecklist $checklist;

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new Event();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    private function generateGenericSelector() : SqlSelector
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn($this->databaseTable . '.*');
        $selector->addSelectColumn('enums.value AS typeName');
        $selector->addSelectColumn('evs.name AS surveyTemplateName');
        $selector->addSelectColumn('evsub.name AS subscriptionTemplateName');
        $selector->addSelectColumn("(select group_concat(COALESCE(eventlocations.type, 'null')) from eventdates left join eventlocations on eventlocations.id = eventdates.locationId where eventdates.eventId = events.id) as locTypes");
        
        $selector->setTable("events");
        $selector->addJoin("left join jsontemplates as evs on evs.type = 'eventsurvey' and evs.id = events.surveyTemplateId ");
        $selector->addJoin("left join jsontemplates as evsub on evsub.type = 'eventsubscription' and evsub.id = events.subscriptionTemplateId ");
        $selector->addJoin("right join enums on enums.type = 'EVENT' and enums.id = events.typeId ");
        $selector->addWhereClause("{$this->databaseTable}.id = ?");
        $selector->addValue('i', $this->id);

        return $selector;
    }

    public function fillPropertiesWithDefaultValues()
    {
        parent::fillPropertiesWithDefaultValues();

        $this->workPlan = new EventWorkPlan();
        $this->workPlan->fillPropertiesWithDefaultValues();
    }

    public function getSingle(mysqli $conn)
    {
        $selector = $this->generateGenericSelector();
        $dataRow = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dataRow))
            return $this->newInstanceFromDataRow($dataRow);
        else
            throw new \SisEpi\Model\Exceptions\DatabaseEntityNotFound('Evento não localizado!', $this->databaseTable);
    }

    public function getSingleDataRow(mysqli $conn)
    {
        $selector = $this->generateGenericSelector();
        $selector->addSelectColumn('MIN(eventdates.date) AS minDate ');
        $selector->addSelectColumn('MAX(eventdates.date) AS maxDate ');
        $selector->addJoin(' INNER JOIN eventdates ON eventdates.eventId = events.id ');
        $selector->setGroupBy("{$this->databaseTable}.id ");
        $dataRow = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dataRow))
            return $dataRow;
        else
            throw new \SisEpi\Model\Exceptions\DatabaseEntityNotFound('Evento não localizado!', $this->databaseTable);
    }

    public function getMultiplePartially(mysqli $conn, $page, $numResultsOnPage, $_orderBy, $searchKeywords) : array
    {
        $selector = new SqlSelector();
        $selector->addSelectColumn('events.id ');
        $selector->addSelectColumn('events.name ');
        $selector->addSelectColumn('MIN(eventdates.date) AS date ');
        $selector->addSelectColumn('enums.value AS typeName ');
        $selector->addSelectColumn("(SELECT group_concat(COALESCE(eventlocations.type, 'null')) from eventdates left join eventlocations on eventlocations.id = eventdates.locationId where eventdates.eventId = events.id) as locTypes ");

        $selector->setTable($this->databaseTable);

        $selector->addJoin('INNER JOIN eventdates ON eventdates.eventId = events.id ');
        $selector->addJoin("inner JOIN enums on enums.type = 'EVENT' and enums.id = events.typeId ");

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
                $selector->setOrderBy('eventdates.date DESC ');
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
        $getter = new EventDate();
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
        $getter = new EventAttachment();
        while ($eaId = $result->fetch_row())
        {
            $getter->id = $eaId[0];
            $eventAttachment = $getter->getSingle($conn);
            $this->eventAttachments[] = $eventAttachment;
        }

        $stmt = $conn->prepare('SELECT id FROM eventworkplans WHERE eventId = ? ');
        $stmt->bind_param('i', $evId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $getter = new \SisEpi\Model\Events\EventWorkPlan();
        $wpId = $result->fetch_row()[0] ?? null;
        
        if (!empty($wpId))
        {
            $getter->id = $wpId;
            $this->workPlan = $getter->getSingle($conn);
            
            if ($recursive)
                $this->workPlan->fetchSubEntities($conn);
        }

        $getter = new EventChecklist();
        $getter->id = $this->properties->checklistId->getValue();

        if ($getter->id)
            $this->checklist = $getter->getSingle($conn);

        $getter = new EventSurvey();
        $this->eventSurveys = $getter->getAllOfEvent($conn, $this->properties->id->getValue());
    }

    public function fillPropertiesFromFormInput($post, $files = null)
    {
        parent::fillPropertiesFromFormInput($post, $files);
        
        if ($this->otherProperties->eventDatesChangesReport)
        {
            $this->eventDatesChangesReport = new EntitiesChangesReport($this->otherProperties->eventDatesChangesReport, EventDate::class);
        }

        if ($this->otherProperties->eventDatesChangesReport)
        {
            $this->eventAttachmentsChangesReport = new EntitiesChangesReport($this->otherProperties->eventAttachmentsChangesReport, EventAttachment::class);
            $this->eventAttachmentsChangesReport->callMethodForAll('setPostFiles', $files);
        }

        $this->workPlan = new \SisEpi\Model\Events\EventWorkPlan();
        $this->workPlan->fillPropertiesFromFormInput($post, $files);

        $this->checklist = new \SisEpi\Model\Events\EventChecklist();
        $this->checklist->fillPropertiesFromFormInput($post);
        $this->checklistId = $this->checklist->id;
    }

    public function afterDatabaseInsert(mysqli $conn, $insertResult)
    {
        if ($this->eventDatesChangesReport)
        {
            $this->eventDatesChangesReport->setPropertyValueForAll('eventId', $insertResult['newId']);
            $insertResult['affectedRows'] += $this->eventDatesChangesReport->applyToDatabase($conn);
        }

        if ($this->eventAttachmentsChangesReport)
        {
            $this->eventAttachmentsChangesReport->setPropertyValueForAll('eventId', $insertResult['newId']);
            $insertResult['affectedRows'] += $this->eventAttachmentsChangesReport->applyToDatabase($conn);
        }
        
        $insertResult['affectedRows'] += setChecklistActionOnEvent($insertResult['newId'], $this->otherProperties->selEventChecklistActions, $conn);

        if (isset($this->workPlan))
        {
            $this->workPlan->eventId = $insertResult['newId'];
            $insertResult['affectedRows'] += $this->workPlan->save($conn)['affectedRows'];
        }

        return $insertResult;
    }

    public function afterDatabaseUpdate(mysqli $conn, $updateResult)
    {
        if ($this->eventDatesChangesReport)
        {
            $this->eventDatesChangesReport->setPropertyValueForAll('eventId', $this->properties->id->getValue());
            $updateResult['affectedRows'] += $this->eventDatesChangesReport->applyToDatabase($conn);
        }

        if ($this->eventAttachmentsChangesReport)
        {
            $this->eventAttachmentsChangesReport->setPropertyValueForAll('eventId', $this->properties->id->getValue());
            $updateResult['affectedRows'] += $this->eventAttachmentsChangesReport->applyToDatabase($conn);
        }
        
        if (isset($this->workPlan)) $updateResult['affectedRows'] += $this->workPlan->save($conn)['affectedRows'];
        if (isset($this->checklist)) $updateResult['affectedRows'] += $this->checklist->save($conn)['affectedRows'];

        $updateResult['affectedRows'] += setChecklistActionOnEvent($this->properties->id->getValue(), $this->otherProperties->selEventChecklistActions, $conn);

        return $updateResult;
    }

    public function afterDatabaseDelete(mysqli $conn, $deleteResult)
    {
        foreach ($this->eventDates as $ed)
            $deleteResult['affectedRows'] += $ed->delete($conn)['affectedRows'];

        foreach ($this->eventAttachments as $ea)
            $deleteResult['affectedRows'] += $ea->delete($conn)['affectedRows'];

        if (isset($this->workPlan))
            $deleteResult['affectedRows'] += $this->workPlan->delete($conn)['affectedRows'];
        
        if (isset($this->checklist))
            $deleteResult['affectedRows'] += $this->checklist->delete($conn)['affectedRows'];

        foreach ($this->eventSurveys as $es)
            $deleteResult['affectedRows'] += $es->delete($conn)['affectedRows'];

        return $deleteResult;
    }
}