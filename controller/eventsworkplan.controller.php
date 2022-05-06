<?php

require_once("model/GenericObjectFromDataRow.class.php");

final class eventsworkplan extends BaseController
{
    public function pre_view()
    {
        $this->title = "SisEPI - Ver plano de trabalho";
		$this->subtitle = "Ver plano de trabalho";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 4;
    }

    public function view()
    {
        require_once("model/database/students.database.php");
        require_once("model/database/events.database.php");
        require_once("model/database/certificates.database.php");
        
        $eventId = $this->getActionData('eventId') ?? 0;

        if ($this->wasPageCalledDirectly())
        {
            require_once("model/Event.EventDate.EventAttachment.class.php");
            $eventObject = null;
            try
            {
                $eventObject = new Event($eventId);
            }
            catch (Exception $e)
            {
                $eventObject = null;
                $this->pageMessages[] = $e->getMessage();
            }
            $this->view_PageData['eventObj'] = $eventObject;
        }
        
        $conn = createConnectionAsEditor();
        $this->view_PageData['subscriptionCount'] = getSubscriptionsCount($eventId, $conn);
        $this->view_PageData['presentStudentsCount'] = getParticipationNumber($eventId, $conn);
        $this->view_PageData['generatedCertificatesCount'] = getCertificatesCount($eventId, $conn);
        $this->view_PageData['availableCertificatesCount'] = getAvailableCertificatesCount($eventId, $conn);
        $conn->close();
    }

    public function pre_edit()
    {
        $this->title = "SisEPI - Editar plano de trabalho";
		$this->subtitle = "Editar plano de trabalho";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 2;
    }

    public function edit()
    {
        if ($this->wasPageCalledDirectly())
        {
            require_once("model/Event.EventDate.EventAttachment.class.php");

            $eventId = $this->getActionData('eventId') ?? 0;
            $eventObject = null;
            try
            {
                $eventObject = new Event($eventId);
            }
            catch (Exception $e)
            {
                $eventObject = null;
                $this->pageMessages[] = $e->getMessage();
            }
            $this->view_PageData['eventObj'] = $eventObject;
        }
    }
}