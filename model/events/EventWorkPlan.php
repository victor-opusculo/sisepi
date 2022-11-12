<?php
namespace Model\Events;

use DataEntity;
use DataProperty;
use EntitiesChangesReport;
use mysqli;

require_once __DIR__ . '/../DataEntity.php';
require_once __DIR__ . '/../EntitiesChangesReport.php';
require_once __DIR__ . '/EventWorkPlanAttachment.php';

class EventWorkPlan extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('workplanId', null, DataProperty::MYSQL_INT),
            'eventId' => new DataProperty('eventId', null, DataProperty::MYSQL_INT),
            'programName' => new DataProperty('programName', null, DataProperty::MYSQL_STRING),
            'targetAudience' => new DataProperty('targetAudience', null, DataProperty::MYSQL_STRING),
            'duration' => new DataProperty('duration', null, DataProperty::MYSQL_STRING),
            'resources' => new DataProperty('resources', null, DataProperty::MYSQL_STRING),
            'coordinators' => new DataProperty('coordinators', null, DataProperty::MYSQL_STRING),
            'team' => new DataProperty('team', null, DataProperty::MYSQL_STRING),
            'assocTeam' => new DataProperty('assocTeam', null, DataProperty::MYSQL_STRING),
            'legalSubstantiation' => new DataProperty('legalSubstantiation', null, DataProperty::MYSQL_STRING),
            'eventObjective' => new DataProperty('eventObjective', null, DataProperty::MYSQL_STRING),
            'specificObjective' => new DataProperty('specificObjective', null, DataProperty::MYSQL_STRING),
            'manualCertificatesInfos' => new DataProperty('manualCertificatesInfos', null, DataProperty::MYSQL_STRING),
            'observations' => new DataProperty('observations', null, DataProperty::MYSQL_STRING),
            'eventDescription' => new DataProperty('eventDescription', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'eventworkplans';
    protected string $formFieldPrefixName = 'eventworkplans';
    protected array $primaryKeys = ['id'];

    public array $workPlanAttachments = [];

    protected ?EntitiesChangesReport $eventWorkPlanAttachmentsChangesReport;

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new EventWorkPlan();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function fetchSubEntities(mysqli $conn)
    {
        $stmt = $conn->prepare('SELECT id FROM eventworkplanattachments WHERE workPlanId = ? ');
        $wpId = $this->properties->id->getValue();
        $stmt->bind_param('i', $wpId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $getter = new EventWorkPlanAttachment();
        while ($attId = $result->fetch_row())
        {
            $getter->id = $attId[0];
            $att = $getter->getSingle($conn);
            $this->workPlanAttachments[] = $att;
        }
        $result->close();
    }

    public function fillPropertiesFromFormInput($post, $files = null)
    {
        parent::fillPropertiesFromFormInput($post, $files);

        if ($json = $this->otherProperties->eventWorkPlanAttachmentsChangesReport)
        {
            $this->eventWorkPlanAttachmentsChangesReport = new EntitiesChangesReport($json, EventWorkPlanAttachment::class);
            $this->eventWorkPlanAttachmentsChangesReport->callMethodForAll('setPostFiles', $files);
        }
    }

    public function afterDatabaseInsert(mysqli $conn, $insertResult)
    {
        if ($this->eventWorkPlanAttachmentsChangesReport)
        {
            $this->eventWorkPlanAttachmentsChangesReport->setPropertyValueForAll('workPlanId', $insertResult['newId']);
            $insertResult['affectedRows'] += $this->eventWorkPlanAttachmentsChangesReport->applyToDatabase($conn);
        }

        return $insertResult;
    }

    public function afterDatabaseUpdate(mysqli $conn, $updateResult)
    {
        if ($this->eventWorkPlanAttachmentsChangesReport)
        {
            $this->eventWorkPlanAttachmentsChangesReport->setPropertyValueForAll('workPlanId', $this->properties->id->getValue());
            $updateResult['affectedRows'] += $this->eventWorkPlanAttachmentsChangesReport->applyToDatabase($conn);
        }

        return $updateResult;
    }
}