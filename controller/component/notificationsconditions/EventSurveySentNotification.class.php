<?php
namespace Controller\Component\NotificationsConditions;

use SqlSelector;

require_once __DIR__ . '/../NotificationConditions.class.php';
require_once __DIR__ . '/../../../model/Events/Event.php';
require_once __DIR__ . '/../../../model/Events/EventSurveyTemplate.php';

final class EventSurveySentNotification extends \NotificationConditions
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
            
            if (!empty($currentConditions['surveyField']))
                $this->surveyFields = $this->convertExpectedSurveyFields((array)$currentConditions['surveyField']);

            if (!empty($currentConditions['operators']))
                $this->logicOperators = $currentConditions['operators']; 
        }

        $getter = new \SisEpi\Model\Events\EventSurveyTemplate();
        $this->templateFieldsTitlesAndValues = $getter->getAllQuestionsAndPossibleValues($this->connection);
    }

    protected $name = 'notificationsconditions/EventSurveySentNotification';

    private ?array $events = null;
    private ?array $surveyFields = null;
    private ?array $logicOperators = null;

    private ?array $templateFieldsTitlesAndValues = null;

    private function convertExpectedSurveyFields(array $expFields) : array
    {
        $output = [];
        foreach ($expFields as $title => $values)
            foreach ($values as $value)
                $output[] = [ 'title' => $title, 'value' => $value ];

        return $output;
    }

    protected function getViewVars(): array
    {
        return
        [
            'events' => $this->events,
            'surveyFields' => $this->surveyFields,
            'templateFieldsTitlesAndValues' => $this->templateFieldsTitlesAndValues,
            'logicOperators' => $this->logicOperators
        ];
    }
}