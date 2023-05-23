<?php

namespace SisEpi\Model\Notifications\Classes;

use SisEpi\Model\Notifications\Notification;
use mysqli;

require_once __DIR__ . '/../Notification.php';
require_once __DIR__ . '/../../exceptions.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

final class EventTestSentNotification extends Notification
{
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        $this->module = 'EVENT';
        $this->id = 3;
        $this->name = 'Eventos: Questionário de avaliação preenchido';
        $this->defaultIconFilePath = '/pics/form-by-FlatIcons.png';
    }

    public const CONDITIONS_COMPONENT_NAME = null;

    protected string $studentName;
    protected int $testId;
    protected \SisEpi\Model\Events\Event $event;

    protected function prePush(mysqli $conn) : array
    {           
        try { $notFromDB = $this->getSingle($conn); } catch (\SisEpi\Model\Exceptions\DatabaseEntityNotFound $e) { $notFromDB = null; }

        $sent = new \SisEpi\Model\Notifications\SentNotification();
        $sent->title = "Questionário de avaliação completado";
        $sent->description = "{$this->studentName} completou questionário de avaliação do evento \"{$this->event->name}\"";
        $sent->iconFilePath = $notFromDB->defaultIconFilePath ?? $this->defaultIconFilePath;
        $sent->linkUrlInfos = \URL\JSONStructURLGenerator::generateSystemURL('events4', 'viewtest', $this->testId);

        return [ $sent, 0 ];
    }

    public function checkConditions(?array $conditions): bool
    {
        return true;
    }
}