<?php

namespace SisEpi\Model\Events;

use mysqli;
use SisEpi\Model\DataEntity;
use SisEpi\Model\DataProperty;
use SisEpi\Model\DynamicObject;
use SisEpi\Model\Exceptions\DatabaseEntityNotFound;
use SisEpi\Model\SqlSelector;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../exceptions.php';

class EventCompletedTest extends DataEntity
{
    public function __construct()
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('hidTestId', null, DataProperty::MYSQL_INT),
            'subscriptionId' => new DataProperty('hidSubscriptionId', null, DataProperty::MYSQL_INT),
            'email' => new DataProperty('hidEmail', null, DataProperty::MYSQL_STRING, true),
            'eventId' => new DataProperty('hidEventId', null, DataProperty::MYSQL_INT),
            'testData' => new DataProperty('hidTestDataJson', null, DataProperty::MYSQL_STRING)
        ];
    }

    protected string $databaseTable = 'eventcompletedtests';
    protected string $formFieldPrefixName = 'eventcompletedtests';
    protected array $primaryKeys = ['id' ];

    protected function newInstanceFromDataRow($dataRow)
    {
        $new = new self();
        $new->fillPropertiesFromDataRow($dataRow);
        return $new;
    }

    public function existsFilledTest(mysqli $conn, ?bool $eventRequiresSubscription = null) : bool
    {
        if (!isset($eventRequiresSubscription))
        {
            $eventGetter = new Event();
            $eventGetter->id = $this->properties->eventId->getValue();
            $event = $eventGetter->getSingle($conn);
            $eventRequiresSubscription = $event->subscriptionListNeeded === 1;
        }

        $selector = (new SqlSelector)
        ->addSelectColumn("COUNT(*)")
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->databaseTable}.eventId = ? ")
        ->addValue('i', $this->properties->eventId->getValue());

        if ($eventRequiresSubscription)
        {
            $selector = $selector
            ->addWhereClause("AND {$this->databaseTable}.subscriptionId = ?")
            ->addValue('i', $this->properties->subscriptionId->getValue());
        }
        else
        {
            $selector = $selector
            ->addSelectColumn("AND {$this->databaseTable}.email = AES_ENCRYPT(lower(?), '{$this->encryptionKey}')")
            ->addValue('s', $this->properties->email->getValue());
        }

        return (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE) > 0;
    }

    public function getSingleFromEventAndEmail_SubsId(mysqli $conn, ?bool $eventRequiresSubscription = null) : self
    {
        $requSubs = $eventRequiresSubscription;
        if (!isset($eventRequiresSubscription))
        {
            $eventGetter = new Event();
            $eventGetter->id = $this->properties->eventId->getValue();
            $event = $eventGetter->getSingle($conn);
            $requSubs = $event->subscriptionListNeeded === 1;
        }

        $selector = (new SqlSelector)
        ->addSelectColumn($this->getSelectQueryColumnName("id"))
        ->addSelectColumn($this->getSelectQueryColumnName("eventId"))
        ->addSelectColumn($this->getSelectQueryColumnName("subscriptionId"))
        ->addSelectColumn($this->getSelectQueryColumnName("email"))
        ->addSelectColumn($this->getSelectQueryColumnName("testData"))
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->databaseTable}.eventId = ? ")
        ->addValue('i', $this->properties->eventId->getValue());

        if ($requSubs)
        {
            $selector = $selector
            ->addWhereClause("AND {$this->databaseTable}.subscriptionId = ?")
            ->addValue('i', $this->properties->subscriptionId->getValue());
        }
        else
        {
            $selector = $selector
            ->addSelectColumn("AND {$this->databaseTable}.email = AES_ENCRYPT(lower(?), '{$this->encryptionKey}')")
            ->addValue('s', $this->properties->email->getValue());
        }

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new DatabaseEntityNotFound("Avaliação não localizada!", $this->databaseTable);
    }

    public function isApproved() : array
    {
        $json = $this->properties->testData->getValue();
        $decoded = json_decode($json);

        $minPercentRequired = $decoded->percentForApproval;
        $percentArchieved = $decoded->grade;

        return [ $percentArchieved >= $minPercentRequired, $percentArchieved, $minPercentRequired ];
    }

    public function getClassTimeHours() : int
    {
        $json = $this->properties->testData->getValue();
        $decoded = json_decode($json);

        return $decoded->classTimeHours ?? 0;
    }

    public function createFromTestPage(mysqli $conn)
    {
        $studentInfos = new DynamicObject();

        $eventGetter = new \SisEpi\Model\Events\Event();
        $eventGetter->id = $this->properties->eventId->getValue();
        $event = $eventGetter->getSingle($conn);

        if ((bool)$event->subscriptionListNeeded)
        {
            $subscriptionGetter = new \SisEpi\Model\Events\EventSubscription();
            $subscriptionGetter->eventId = $event->id;
            $subscriptionGetter->email = $this->otherProperties->hidEmail;
            $subscriptionGetter->setCryptKey($this->encryptionKey);
            $subscription = $subscriptionGetter->getSingleFromEventAndEmail($conn);

            $this->properties->subscriptionId->setValue($subscription->id);

            $studentInfos->name = $subscription->name;
            $studentInfos->email = $subscription->email;
        }
        else
        {
            $presenceRecordGetter = new \SisEpi\Model\Events\EventPresenceRecord();
            $presenceRecordGetter->setCryptKey($this->encryptionKey);
            $presenceRecordGetter->email = $this->otherProperties->hidEmail;
            $studentInfos->name = $presenceRecordGetter->getNameFromEmail($conn);
            $studentInfos->email = $this->otherProperties->hidEmail;

            $this->properties->email->setValue($this->otherProperties->hidEmail);
        }

        $templateGetter = new \SisEpi\Model\Events\EventTestTemplate();
        $templateGetter->id = $event->testTemplateId;
        $templateObject = $templateGetter->getSingle($conn);

        $template = json_decode($templateObject->templateJson, true, 512, JSON_THROW_ON_ERROR);
        $applied = $this->applyAnswersToTemplate($template, $this->otherProperties->testQuestions);

        $this->properties->testData->setValue(json_encode($applied));

        return [ 'event' => $event, 'studentInfos' => $studentInfos ]; 
    }

    private function applyAnswersToTemplate(array $template, array $answers) : array
    {
        $correctQuestions = 0;
        foreach ($answers as $qIndex => $ans)
        {
            $template['questions'][$qIndex]['studentAnswer'] = (int)$ans;

            if ($template['questions'][$qIndex]['correctAnswer'] === (int)$ans)
                $correctQuestions++;
        }

        $template['grade'] = ($correctQuestions / count($template['questions'])) * 100;

        return $template;
    }
}