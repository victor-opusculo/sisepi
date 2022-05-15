<?php

require_once("model/GenericObjectFromDataRow.class.php");
require_once("model/database/eventchecklists.database.php");

final class eventchecklists extends BaseController
{
    public function pre_view()
    {
        $this->title = "SisEPI - Ver checklist";
		$this->subtitle = "Ver checklist";

		$this->moduleName = "CHKLS";
		$this->permissionIdRequired = 3;
    }

    public function view()
    {
		$checklistDataObject = null;
		$responsibleUsersList = null;
		$eventOrEventDateInfos = null;

		$conn = createConnectionAsEditor();
		try
		{
			$checklistDataObject = new GenericObjectFromDataRow(getSingleChecklist($this->getActionData('id'), $conn));
			$checklistDataObject->checklistJson = json_decode($checklistDataObject->checklistJson);
			$responsibleUsersList = getResponsibleUsersList($conn);
			if ($this->wasPageCalledDirectly()) $eventOrEventDateInfos = getEventOrEventDateInfos($this->getActionData('id'), $conn);
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
			$checklistDataObject = null;
		}
		finally { $conn->close(); }
			
		$this->view_PageData['checklistDataObj'] = $checklistDataObject;
		$this->view_PageData['responsibleUsersList'] = $responsibleUsersList;
		$this->view_PageData['eventOrEventDateInfos'] = $eventOrEventDateInfos;
    }

    public function pre_edit()
    {
        $this->title = "SisEPI - Editar checklist";
		$this->subtitle = "Editar checklist";
		
		$this->moduleName = "CHKLS";
		$this->permissionIdRequired = 1;
    }

    public function edit()
    {
		require_once("controller/eventchecklisttemplates.controller.php");
		
		$checklistId = (int)$this->getActionData("id") ?? 0;
		$checklistDataObject = null;
		$responsibleUsersList = null;
		$editChecklistPage = new eventchecklisttemplates("editchecklist");
		$eventOrEventDateInfos = null;

		$conn = $this->getActionData("conn") ?? createConnectionAsEditor();
		try
		{
			$checklistDataObject = new GenericObjectFromDataRow(getSingleChecklist($checklistId, $conn));
			$checklistDataObject->checklistJson = json_decode($checklistDataObject->checklistJson);
			$responsibleUsersList = getResponsibleUsersList($conn);
			if ($this->wasPageCalledDirectly()) $eventOrEventDateInfos = getEventOrEventDateInfos($this->getActionData('id'), $conn);
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
			$checklistDataObject = null;
		}
		finally
		{
			if (empty($this->getActionData("conn"))) $conn->close();
		}

		$this->view_PageData['responsibleUsersList'] = $responsibleUsersList;
		$this->view_PageData['checklistObj'] = $checklistDataObject->checklistJson ?? null;
		$editChecklistPage->inheritViewPageData($this->view_PageData);
		$this->view_PageData['editChecklistPage'] = $editChecklistPage;
		$this->view_PageData['checklistDataObject'] = $checklistDataObject;
		$this->view_PageData['createForm'] = $this->wasPageCalledDirectly();
		$this->view_PageData['eventOrEventDateInfos'] = $eventOrEventDateInfos;
    }

}