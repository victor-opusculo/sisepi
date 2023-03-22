<?php

namespace SisEpi\Model\Notifications\Classes;

use SisEpi\Model\Notifications\Notification;
use mysqli;

require_once __DIR__ . '/../Notification.php';
require_once __DIR__ . '/../../vereadormirim/Document.php';
require_once __DIR__ . '/../../vereadormirim/VmParent.php';
require_once __DIR__ . '/../../vereadormirim/Student.php';

final class VmParentSignedDocNotification extends Notification
{
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);

        $this->module = 'VMPAR';
        $this->id = 1;
        $this->name = 'Vereador Mirim: Assinatura em documento vinculado a vereador mirim feita';
        $this->defaultIconFilePath = '/pics/form-by-FlatIcons.png';
    }

    public const CONDITIONS_COMPONENT_NAME = "VmParentSignedDocNotification";

    protected \SisEpi\Model\VereadorMirim\Document $document;
    protected \SisEpi\Model\VereadorMirim\VmParent $vmParent;
    protected \SisEpi\Model\VereadorMirim\Student $vmStudent;

    protected function prePush(mysqli $conn) : array
    {           
        try { $notFromDB = $this->getSingle($conn); } catch (\SisEpi\Model\Exceptions\DatabaseEntityNotFound $e) { $notFromDB = null; }

        $sent = new \SisEpi\Model\Notifications\SentNotification();
        $sent->title = "Pai/Responsável de vereador mirim assinou documento";
        $sent->description = "{$this->vmParent->name} assinou documento vinculado ao vereador mirim \"{$this->vmStudent->name}\"";
        $sent->iconFilePath = $notFromDB->defaultIconFilePath ?? $this->defaultIconFilePath;
        $sent->linkUrlInfos = \URL\JSONStructURLGenerator::generateSystemURL('vereadormirimstudents', 'viewdocument', $this->document->id);

        return [ $sent, 0 ];
    }

    public function checkConditions(?array $conditions): bool
    {
        if (!isset($conditions)) return true;

        $checkVmLegislatureId = function($vmLegIds)
        {
            if (!is_array($vmLegIds)) return true;
            if (empty($vmLegIds)) return true;
            return in_array($this->vmStudent->vmLegislatureId, $vmLegIds);
        };

        $checkVmParentId = function($vmParentIds)
        {
            if (!is_array($vmParentIds)) return true;
            if (empty($vmParentIds)) return true;
            return in_array($this->vmParent->id, $vmParentIds);
        };

        $passLegislatureId = $checkVmLegislatureId($conditions['vmLegislatureId']);
        $passVmParentId = $checkVmParentId($conditions['vmParentId']);

        $pass = $passLegislatureId;
        switch ($conditions['operators'][0])
        {
            case 'and': $pass = $pass && $passVmParentId; break;
            case 'or': $pass = $pass || $passVmParentId; break;
        }

        return $pass;
    }
}