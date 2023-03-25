<?php
namespace SisEpi\Controller\Component\NotificationsConditions;

use SqlSelector;

require_once __DIR__ . '/../NotificationConditions.class.php';
require_once __DIR__ . '/../../../model/Events/Event.php';

final class EventSubscriptionNotification extends \NotificationConditions
{
    public function __construct(array $properties = null)
    {
        parent::__construct($properties);

        $currentConditions = $this->uNotSubscription->getConditions();
        if (isset($currentConditions))
        {
            if (!empty($currentConditions['eventId']))
                $this->events = array_map(function($eventId)
                {
                    $ev = new \SisEpi\Model\Events\Event();
                    $ev->id = $eventId;
                    $ret = null;
                    try { $ret = $ev->getSingle($this->connection); } catch (\SisEpi\Model\Exceptions\DatabaseEntityNotFound $e) { $ret = null; }
                    return $ret; 
                }, $currentConditions['eventId']);

            if (!empty($currentConditions['studentEmail']))
                $this->studentEmails = $currentConditions['studentEmail'];
            
            if (!empty($currentConditions['studentName']))
                $this->studentNames = $currentConditions['studentName'];
            
            if (!empty($currentConditions['studentSubscriptionField']))
                $this->studentSubscriptionFields = $this->convertSubsFieldsToArray((array)$currentConditions['studentSubscriptionField']);

            if (!empty($currentConditions['operators']))
                $this->logicOperators = $currentConditions['operators']; 
        }

        $this->subscriptionIdentifiers = $this->getSubscriptionTemplatesIdentifiers();
    }

    protected $name = 'notificationsconditions/EventSubscriptionNotification';

    private ?array $events = null;
    private ?array $studentEmails = null;
    private ?array $studentNames = null;
    private ?array $studentSubscriptionFields = null;
    private ?array $logicOperators = null;
    private ?array $subscriptionIdentifiers = null;

    private function getSubscriptionTemplatesIdentifiers() : array
    {
        $selector = new \SisEpi\Model\SqlSelector();
        $selector->addSelectColumn('templateJson');
        $selector->setTable('jsontemplates');
        $selector->addWhereClause("type = 'eventsubscription' ");
        $drs = $selector->run($this->connection, \SisEpi\Model\SqlSelector::RETURN_ALL_ASSOC);

        $identifiers = [];
        foreach ($drs as $dr)
        {
            $decoded = json_decode($dr['templateJson']);
            foreach ($decoded->questions as $quest)
                if (!in_array($quest->identifier, $identifiers))
                    $identifiers[] = $quest->identifier;
        }

        return $identifiers;
    }

    private function convertSubsFieldsToArray(array $fields) : array
    {
        $output = [];
        foreach ($fields as $k => $v)
            foreach ($v as $val)
                $output[] = [ 'identifier' => $k, 'value' => $val ];

        return $output;
    }

    protected function getViewVars(): array
    {
        return
        [
            'events' => $this->events,
            'studentEmails' => $this->studentEmails,
            'studentNames' => $this->studentNames,
            'subscriptionIdentifiers' => $this->subscriptionIdentifiers,
            'studentSubscriptionFields' => $this->studentSubscriptionFields,
            'logicOperators' => $this->logicOperators
        ];
    }
}