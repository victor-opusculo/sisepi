<?php

namespace SisEpi\Model\Notifications\Classes;

use SisEpi\Model\Notifications\Notification;
use mysqli;

require_once __DIR__ . '/../Notification.php';
require_once __DIR__ . '/../../events/EventSurveyTemplate.php';

final class EventSurveySentNotification extends Notification
{
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        $this->module = 'EVENT';
        $this->id = 2;
        $this->name = 'Eventos: Pesquisa de satisfação preenchida';
        $this->defaultIconFilePath = '/pics/notifications/stars-by-freepik.png';
    }

    public const CONDITIONS_COMPONENT_NAME = "EventSurveySentNotification";

    protected int $eventId;
    protected int $surveyId;
    protected string $eventName;
    protected array $surveyData;

    protected function prePush(mysqli $conn) : array
    {   

        try { $notFromDB = $this->getSingle($conn); } catch (\SisEpi\Model\Exceptions\DatabaseEntityNotFound $e) { $notFromDB = null; }

        $sent = new \SisEpi\Model\Notifications\SentNotification();
        $sent->title = "Pesquisa de satisfação preenchida";
        $sent->description = "Participante preencheu pesquisa de satisfação do evento \"{$this->eventName}\"";
        $sent->iconFilePath = $notFromDB->defaultIconFilePath ?? $this->defaultIconFilePath;
        $sent->linkUrlInfos = \URL\JSONStructURLGenerator::generateSystemURL('events3', 'viewsinglesurvey', $this->surveyId);

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

        $checkSurveyField = function($fieldsConditions)
        {
            if (empty($fieldsConditions)) return true;

            $checkBlock = function(array $questionBlock, string $expectedTitle, array $expectedValues)
            {
                foreach ($questionBlock as $quest)
                {
                    if ($quest['title'] === $expectedTitle && \SisEpi\Model\Events\EventSurveyTemplate::checkIfValueIsWithinArray($quest, $expectedValues))
                        return true;
                }
                return false;
            };

            foreach ($fieldsConditions as $title => $values)
            {
                if ($checkBlock($this->surveyData['head'] ?? [], $title, $values)) return true;
                if ($checkBlock($this->surveyData['body'] ?? [], $title, $values)) return true;
                if ($checkBlock($this->surveyData['foot'] ?? [], $title, $values)) return true;
            }
            return false;
        };

        $passEventId = $checkEventId($conditions['eventId']);
        $passSurveyField = $checkSurveyField($conditions['surveyField']);

        $pass = $passEventId;
        switch ($conditions['operators'][0])
        {
            case 'and': $pass = $pass && $passSurveyField; break;
            case 'or': $pass = $pass || $passSurveyField; break;
        }

        return $pass;
    }
}