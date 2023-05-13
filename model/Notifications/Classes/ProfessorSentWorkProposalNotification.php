<?php

namespace SisEpi\Model\Notifications\Classes;

use SisEpi\Model\Notifications\Notification;
use mysqli;

require_once __DIR__ . '/../Notification.php';
require_once __DIR__ . '/../../exceptions.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

final class ProfessorSentWorkProposalNotification extends Notification
{
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        $this->module = 'PROFE';
        $this->id = 2;
        $this->name = 'Docentes: Plano de aula enviado pelo próprio docente';
        $this->defaultIconFilePath = '/pics/professor-work-proposals-by-Freepik.png';
    }

    public const CONDITIONS_COMPONENT_NAME = null;

    protected \SisEpi\Model\Professors\Professor $professor;
    protected \SisEpi\Model\Professors\ProfessorWorkProposal $workProposal;

    protected function prePush(mysqli $conn) : array
    {           
        try { $notFromDB = $this->getSingle($conn); } catch (\SisEpi\Model\Exceptions\DatabaseEntityNotFound $e) { $notFromDB = null; }

        $sent = new \SisEpi\Model\Notifications\SentNotification();
        $sent->title = "Docente enviou plano de aula";
        $sent->description = "{$this->professor->name} enviou plano de aula com o tema \"{$this->workProposal->name}\"";
        $sent->iconFilePath = $notFromDB->defaultIconFilePath ?? $this->defaultIconFilePath;
        $sent->linkUrlInfos = \URL\JSONStructURLGenerator::generateSystemURL('professors2', 'viewworkproposal', $this->workProposal->id);

        return [ $sent, 0 ];
    }

    public function checkConditions(?array $conditions): bool
    {
        return true;
    }
}