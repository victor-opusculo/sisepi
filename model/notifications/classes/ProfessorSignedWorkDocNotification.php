<?php

namespace SisEpi\Model\Notifications\Classes;

use SisEpi\Model\Notifications\Notification;
use mysqli;

require_once __DIR__ . '/../Notification.php';

final class ProfessorSignedWorkDocNotification extends Notification
{
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        $this->module = 'PROFE';
        $this->id = 1;
        $this->name = 'Docentes: Assinatura em documentação de empenho feita';
        $this->defaultIconFilePath = '/pics/form-by-FlatIcons.png';
    }

    public const CONDITIONS_COMPONENT_NAME = "ProfessorSignedWorkDocNotification";

    protected int $workProposalId;
    protected int $workSheetId;
    protected int $professorId;

    private string $professorName;

    protected function prePush(mysqli $conn) : array
    {   
        require_once __DIR__ . '/../../professors/ProfessorWorkProposal.php';
        require_once __DIR__ . '/../../professors/Professor.php';

        $wpgetter = new \SisEpi\Model\Professors\ProfessorWorkProposal();
        $wpgetter->id = $this->workProposalId;
        $wpgetter->setCryptKey(getCryptoKey());
        $wp = $wpgetter->getSingle($conn);

        $profGetter = new \SisEpi\Model\Professors\Professor();
        $profGetter->id = $this->professorId;
        $profGetter->setCryptKey(getCryptoKey());
        $prof = $profGetter->getSingle($conn);

        $this->professorName = $prof->name;
        
        try { $notFromDB = $this->getSingle($conn); } catch (\SisEpi\Model\Exceptions\DatabaseEntityNotFound $e) { $notFromDB = null; }

        $sent = new \SisEpi\Model\Notifications\SentNotification();
        $sent->title = "Docente assinou documentação de empenho";
        $sent->description = "{$prof->name} assinou documentação de empenho vinculada ao plano de aula \"{$wp->name}\"";
        $sent->iconFilePath = $notFromDB->defaultIconFilePath ?? $this->defaultIconFilePath;
        $sent->linkUrlInfos = \URL\JSONStructURLGenerator::generateSystemURL('professors2', 'viewworksheet', $this->workSheetId);

        return [ $sent, 0 ];
    }

    public function checkConditions(?array $conditions): bool
    {
        if (!isset($conditions)) return true;

        $checkWorkProposalId = function($workProposalIds)
        {
            if (!is_array($workProposalIds)) return true;
            if (empty($workProposalIds)) return true;
            return in_array($this->workProposalId, $workProposalIds);
        };

        $checkProfessorId = function($professorIds)
        {
            if (!is_array($professorIds)) return true;
            if (empty($professorIds)) return true;
            return in_array($this->professorId, $professorIds);
        };

        $checkProfessorName = function($names)
        {
            if (empty($names)) return true;

            foreach ($names as $name)
                if (mb_stripos($this->professorName, $name) !== false)
                    return true;

            return false;
        };

        $passWorkProposalId = $checkWorkProposalId($conditions['workProposalId']);
        $passProfessorId = $checkProfessorId($conditions['professorId']);
        $passProfessorName = $checkProfessorName($conditions['professorName']);

        $pass = $passWorkProposalId;
        switch ($conditions['operators'][0])
        {
            case 'and': $pass = $pass && $passProfessorId; break;
            case 'or': $pass = $pass || $passProfessorId; break;
        }

        switch ($conditions['operators'][1])
        {
            case 'and': $pass = $pass && $passProfessorName; break;
            case 'or': $pass = $pass || $passProfessorName; break;
        }

        return $pass;
    }
}