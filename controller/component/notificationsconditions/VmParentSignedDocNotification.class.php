<?php
namespace Controller\Component\NotificationsConditions;

require_once __DIR__ . '/../NotificationConditions.class.php';
require_once __DIR__ . '/../../../model/vereadormirim/Legislature.php';
require_once __DIR__ . '/../../../model/vereadormirim/VmParent.php';

final class VmParentSignedDocNotification extends \NotificationConditions
{
    public function __construct(array $properties = null)
    {
        parent::__construct($properties);

        $currentConditions = $this->uNotSubscription->getConditions();
        if (isset($currentConditions))
        {
            if (!empty($currentConditions['vmLegislatureId']))
                $this->legislatures = array_map(function($legId)
                {
                    $leggetter = new \Model\VereadorMirim\Legislature();
                    $leggetter->id = $legId;
                    $ret = null;
                    try { $ret = $leggetter->getSingle($this->connection); } catch (\Model\Exceptions\DatabaseEntityNotFound $e) { $ret = null; }
                    return $ret; 
                }, $currentConditions['vmLegislatureId']);
            
            if (!empty($currentConditions['vmParentId']))
                $this->vmParents = array_map(function($parentId)
                {
                    $pgetter = new \Model\VereadorMirim\VmParent();
                    $pgetter->id = $parentId;
                    $pgetter->setCryptKey(getCryptoKey());
                    $ret = null;
                    try { $ret = $pgetter->getSingle($this->connection); } catch (\Model\Exceptions\DatabaseEntityNotFound $e) { $ret = null; }
                    return $ret; 
                }, $currentConditions['vmParentId']);

            if (!empty($currentConditions['operators']))
                $this->logicOperators = $currentConditions['operators']; 
        }

    }

    protected $name = 'notificationsconditions/VmParentSignedDocNotification';

    private ?array $legislatures = null;
    private ?array $vmParents = null;
    private ?array $logicOperators = null;

    protected function getViewVars(): array
    {
        return
        [
            'legislatures' => $this->legislatures,
            'vmParents' => $this->vmParents,
            'logicOperators' => $this->logicOperators
        ];
    }
}