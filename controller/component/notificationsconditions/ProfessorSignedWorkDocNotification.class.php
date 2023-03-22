<?php
namespace Controller\Component\NotificationsConditions;

use SqlSelector;

require_once __DIR__ . '/../NotificationConditions.class.php';
require_once __DIR__ . '/../../../model/professors/ProfessorWorkProposal.php';
require_once __DIR__ . '/../../../model/professors/Professor.php';

final class ProfessorSignedWorkDocNotification extends \NotificationConditions
{
    public function __construct(array $properties = null)
    {
        parent::__construct($properties);

        $currentConditions = $this->uNotSubscription->getConditions();
        if (isset($currentConditions))
        {
            if (!empty($currentConditions['workProposalId']))
                $this->workProposals = array_map(function($wpId)
                {
                    $wpgetter = new \SisEpi\Model\Professors\ProfessorWorkProposal();
                    $wpgetter->id = $wpId;
                    $wpgetter->setCryptKey(getCryptoKey());
                    $ret = null;
                    try { $ret = $wpgetter->getSingle($this->connection); } catch (\SisEpi\Model\Exceptions\DatabaseEntityNotFound $e) { $ret = null; }
                    return $ret; 
                }, $currentConditions['workProposalId']);
            
            if (!empty($currentConditions['professorId']))
                $this->professors = array_map(function($profId)
                {
                    $pgetter = new \SisEpi\Model\Professors\Professor();
                    $pgetter->id = $profId;
                    $pgetter->setCryptKey(getCryptoKey());
                    $ret = null;
                    try { $ret = $pgetter->getSingle($this->connection); } catch (\SisEpi\Model\Exceptions\DatabaseEntityNotFound $e) { $ret = null; }
                    return $ret; 
                }, $currentConditions['professorId']);
            
            if (!empty($currentConditions['professorName']))
                $this->professorNames = $currentConditions['professorName'];

            if (!empty($currentConditions['operators']))
                $this->logicOperators = $currentConditions['operators']; 
        }

    }

    protected $name = 'notificationsconditions/ProfessorSignedWorkDocNotification';

    private ?array $workProposals = null;
    private ?array $professors = null;
    private ?array $professorNames = null;
    private ?array $logicOperators = null;

    protected function getViewVars(): array
    {
        return
        [
            'workProposals' => $this->workProposals,
            'professors' => $this->professors,
            'professorNames' => $this->professorNames,
            'logicOperators' => $this->logicOperators
        ];
    }
}