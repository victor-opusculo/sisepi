<?php
namespace SisEpi\Pub\Model\Events;

use Exception;
use mysqli;
use SisEpi\Model\Database\Connection;
use SisEpi\Model\SqlSelector;

use SisEpi\Model\Events\Event;
use SisEpi\Model\Events\EventSubscription;
use SisEpi\Model\Events\EventSurvey;
use SisEpi\Model\Events\EventCompletedTest;
use SisEpi\Model\Events\EventPresenceRecord;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/URL/URLGenerator.php'; //Uses Public!!!

final class CheckPreRequisitesForCertificate
{
    private function __construct() { }

    private static ?EventSubscription $eventSubscription = null;
    private static array $actionsTried = [];

    public static function tryCertificate(string $studentEmail, mysqli $conn, Event $event, bool $tryingThis = false)
    {
        if (empty($event->certificateText) && $tryingThis)
            throw new Exception("Este evento não fornece certificados automaticamente.");

        self::$actionsTried[] = 'certificate';

        self::trySurvey($studentEmail, $conn, $event);

        array_pop(self::$actionsTried);
    }

    public static function trySurvey(string $studentEmail, mysqli $conn, Event $event, bool $tryingThis = false)
    {
        if (!Connection::isId($event->surveyTemplateId) && $tryingThis)
            throw new Exception("A pesquisa de satisfação está desabilitada para este evento.");

        self::$actionsTried[] = 'survey';

        self::tryTest($studentEmail, $conn, $event);

        if (!Connection::isId($event->surveyTemplateId) && !$tryingThis)
        {
            array_pop(self::$actionsTried);
            return;
        }

        $surveyCompletedGetter = new EventSurvey();
        $surveyCompletedGetter->eventId = $event->id;
        $surveyCompletedGetter->setCryptKey(self::getCryptoKey());

        if ((bool)$event->subscriptionListNeeded)
            $surveyCompletedGetter->subscriptionId = self::$eventSubscription->id;
        else
            $surveyCompletedGetter->studentEmail = $studentEmail;

        $survey = $surveyCompletedGetter->existsFilledSurvey($conn, (bool)$event->subscriptionListNeeded);

        if ($survey && $tryingThis)
            throw new Exception("Você já preencheu esta pesquisa de satisfação.");
        else if (!$survey && !$tryingThis)
        {
            array_pop(self::$actionsTried);
            $message = "Você precisa preencher a pesquisa de satisfação antes!";
            header("location:" . \URL\URLGenerator::generateSystemURL('events2', 'fillsurvey', null, [ 'eventId' => $event->id, 'email' => $studentEmail, 'goTo' => self::joinToDoActions(self::$actionsTried), 'messages' => $message ]), true, 303);
            exit();
        }

        array_pop(self::$actionsTried);
    }

    public static function tryTest(string $studentEmail, mysqli $conn, Event $event, bool $tryingThis = false)
    {
        if (!Connection::isId($event->testTemplateId) && $tryingThis)
            throw new Exception("Este evento não tem questionário de avaliação.");

        self::$actionsTried[] = 'test';
        
        self::checkPresence($studentEmail, $conn, $event);

        if (!Connection::isId($event->testTemplateId) && !$tryingThis)
        {
            array_pop(self::$actionsTried);
            return;
        }

        $testCompletedGetter = new EventCompletedTest();
        $testCompletedGetter->eventId = $event->id;
        $testCompletedGetter->setCryptKey(self::getCryptoKey());

        if ((bool)$event->subscriptionListNeeded)
            $testCompletedGetter->subscriptionId = self::$eventSubscription->id;
        else
            $testCompletedGetter->email = $studentEmail;

        $test = $testCompletedGetter->existsFilledTest($conn, (bool)$event->subscriptionListNeeded);

        if ($test)
        {
            $testCompleted = $testCompletedGetter->getSingleFromEventAndEmail_SubsId($conn, (bool)$event->subscriptionListNeeded);

            [ $isApproved, $percent, $minPercentRequired ] = $testCompleted->isApproved();

            $formattedPercent = number_format($percent, 0, ',', '');

            if ($tryingThis)
                throw new Exception("Você já preencheu este questionário de avaliação e acertou {$formattedPercent}% dos {$minPercentRequired}% necessários");

            if (!$isApproved)
                throw new Exception("Você não foi aprovado na avaliação deste evento. Você acertou {$formattedPercent}% dos {$minPercentRequired}% necessários.");
        }
        else if (!$test && !$tryingThis)
        {
            array_pop(self::$actionsTried);
            $message = "Você precisa responder o questionário de avaliação antes!";
            header("location:" . \URL\URLGenerator::generateSystemURL('events2', 'filltest', null, [ 'eventId' => $event->id, 'email' => $studentEmail, 'goTo' => self::joinToDoActions(self::$actionsTried), 'messages' => $message ]), true, 303);
            exit();
        }

        array_pop(self::$actionsTried);
    }

    public static function checkPresence(string $studentEmail, mysqli $conn, Event $event)
    {
        if (!$event->isOver($conn))
            throw new Exception("Este evento ainda não terminou.");

        $presenceRecordGetter = new EventPresenceRecord();
        $presenceRecordGetter->eventId = $event->id;
        $presenceRecordGetter->setCryptKey(self::getCryptoKey());

        $presencePercent = null;

        if ((bool)$event->subscriptionListNeeded)
        {
            $subscriptionGetter = new EventSubscription();
            $subscriptionGetter->email = $studentEmail;
            $subscriptionGetter->eventId = $event->id;
            $subscriptionGetter->setCryptKey(self::getCryptoKey());
            $subscription = self::$eventSubscription = $subscriptionGetter->getSingleFromEventAndEmail($conn);
        }

            $presenceRecordGetter->email = $studentEmail;
            $presencePercent = $presenceRecordGetter->getPresencePercent($conn, (bool)$event->subscriptionListNeeded);

        if (is_null($presencePercent))
            throw new Exception("E-mail não localizado ou presença nula neste evento.");

        $settSelector = (new SqlSelector)
        ->addSelectColumn('value')
        ->setTable('settings')
        ->addWhereClause("name = 'STUDENTS_MIN_PRESENCE_PERCENT'");

        $minPresencePercent = $settSelector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);

        if ($presencePercent < $minPresencePercent)
            throw new Exception("Você não atingou a presença mínima de {$minPresencePercent}%. Sua presença foi de {$presencePercent}%.");
    }

    private static function getCryptoKey() : string
    {
        return Connection::getCryptoKey();
    }

    private static function joinToDoActions(array $actions) : string
    {
        return implode(",", $actions);
    }
}