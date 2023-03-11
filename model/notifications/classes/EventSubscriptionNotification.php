<?php

namespace Model\Notifications\Classes;

use Model\Notifications\Notification;
use Model\Notifications\SentNotification;
use mysqli;

require_once __DIR__ . '/../Notification.php';

final class EventSubscriptionNotification extends Notification
{
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        $this->module = 'EVENT';
        $this->id = 1;
        $this->name = 'Eventos: Nova inscrição feita';
        $this->defaultIconFilePath = '/pics/notifications/bell-by-pixel-perfect.png';
    }

    public const CONDITIONS_COMPONENT_NAME = "EventSubscriptionNotification";

    protected int $eventId;
    protected int $subscriptionId;
    protected string $studentEmail;
    protected string $studentName;
    protected array $studentSubscriptionFields;

    protected function prePush(mysqli $conn) : array
    {   
        require_once __DIR__ . '/../../events/Event.php';

        $eventGetter = new \Model\Events\Event();
        $eventGetter->id = $this->eventId;
        $event = $eventGetter->getSingle($conn);

        try { $notFromDB = $this->getSingle($conn); } catch (\Model\Exceptions\DatabaseEntityNotFound $e) { $notFromDB = null; }

        $sent = new \Model\Notifications\SentNotification();
        $sent->title = "Nova inscrição em evento feita";
        $sent->description = "{$this->studentName} se inscreveu no evento \"{$event->name}\"";
        $sent->iconFilePath = $notFromDB->defaultIconFilePath ?? $this->defaultIconFilePath;
        $sent->linkUrlInfos = \URL\JSONStructURLGenerator::generateSystemURL('events2', 'viewsubscription', $this->subscriptionId);

        return [ $sent, 0 ];
    }

    public function checkConditions(?array $conditions): bool
    {
        if (!isset($conditions)) return true;

        $checkEventId = function($eventIds)
        {
            if (!is_array($eventIds)) return true;
            if (empty($eventIds)) return true;
            return in_array($this->eventId, $eventIds);
        };

        $checkStudentEmail = function($emails)
        {
            if (!is_array($emails)) return true;
            if (empty($emails)) return true;
            return in_array(mb_strtolower($this->studentEmail), $emails);
        };

        $checkStudentName = function($names)
        {
            if (empty($names)) return true;

            foreach ($names as $name)
                if (mb_stripos($this->studentName, $name) !== false)
                    return true;

            return false;
        };

        $checkStudentSubscriptionField = function($fieldsConditions)
        {
            if (empty($fieldsConditions)) return true;

            foreach ($fieldsConditions as $ident => $values)
                foreach ($values as $value)
                    if (isset($this->studentSubscriptionFields[$ident]))
                        if (mb_stripos($this->studentSubscriptionFields[$ident], $value) !== false)
                            return true;
            
            return false;
        };

        $passEventId = $checkEventId($conditions['eventId']);
        $passStudentEmail = $checkStudentEmail($conditions['studentEmail']);
        $passStudentName = $checkStudentName($conditions['studentName']);
        $passStudentSubsField = $checkStudentSubscriptionField($conditions['studentSubscriptionField']);

        $pass = $passEventId;
        switch ($conditions['operators'][0])
        {
            case 'and': $pass = $pass && $passStudentEmail; break;
            case 'or': $pass = $pass || $passStudentEmail; break;
        }

        switch ($conditions['operators'][1])
        {
            case 'and': $pass = $pass && $passStudentName; break;
            case 'or': $pass = $pass || $passStudentName; break;
        }

        switch ($conditions['operators'][2])
        {
            case 'and': $pass = $pass && $passStudentSubsField; break;
            case 'or': $pass = $pass || $passStudentSubsField; break;
        }

        return $pass;
    }
}