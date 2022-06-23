<?php

require_once("model/GenericObjectFromDataRow.class.php");

final class events3 extends BaseController
{
    public function pre_viewcertificates()
    {
        $this->title = "SisEPI - Ver certificados emitidos";
		$this->subtitle = "Ver certificados emitidos";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 11;
    }

    public function viewcertificates()
    {
        require_once("controller/component/DataGrid.class.php");
        require_once("model/database/certificates.database.php");

        $eventId = isset($_GET['eventId']) && isId($_GET['eventId']) ? $_GET['eventId'] : null;
        $certsDataGrid = new DataGridComponent();
        $eventObj = null;
        $certsDataRows = null;
        $certsCount = 0;
		
		$transformDataRowsRules =
		[
			'id' => fn($row) => $row['id'],
			'Nome' => fn($row) => $row['name'] . ( !empty($row['socialName']) ? ' (' . $row['socialName'] . ')' : '' ),
			'E-mail' => fn($row) => $row['email'],
			'Data de emissão' => fn($row) => date_format(date_create($row['dateTime']), "d/m/Y H:i:s")
		];

        $conn = createConnectionAsEditor();
        try
        {
            $eventObj = new GenericObjectFromDataRow(getEventInfos($eventId, $conn));
            $certsDataRows = getCertificates($eventId, (bool)$eventObj->subscriptionListNeeded, $conn);
            $certsDataGrid->dataRows = Data\transformDataRows($certsDataRows, $transformDataRowsRules);
            $certsCount = isset($certsDataRows) ? count($certsDataRows) : 0;

            $certsDataGrid->columnsToHide[] = "id";
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = "Evento não localizado.";
            $this->pageMessages[] = $e->getMessage();
        }
        finally
        {
            $conn->close();
        }
        
        $this->view_PageData['eventObj'] = $eventObj;
        $this->view_PageData['dgComp'] = $certsDataGrid;
        $this->view_PageData['certsCount'] = $certsCount;
    }

    public function pre_subscribe()
    {
        $this->title = "SisEPI - Inscrever participante";
		$this->subtitle = "Inscrever participante";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 12;
    }

    public function subscribe()
    {
        require_once("model/database/students.database.php");
        require_once("model/database/generalsettings.database.php");

        $eventId = isset($_GET['eventId']) && isId($_GET['eventId']) ? $_GET['eventId'] : null;

        $eventSubscriptionListInfosObj = null;
        $consentFormLink = null;
        $consentFormVersion = null;

        $conn = createConnectionAsEditor();
        try
        {
            $eventSubscriptionListInfosDataRow = getEventSubscriptionListInfos($eventId, $conn);
            if ($eventSubscriptionListInfosDataRow === null)
                throw new Exception("Registro não localizado");

            $eventSubscriptionListInfosObj = new GenericObjectFromDataRow($eventSubscriptionListInfosDataRow);
            if (!(bool)$eventSubscriptionListInfosObj->subscriptionListNeeded)
                throw new Exception("Este evento não usa lista de inscrição");

            $consentFormLink = readSetting('STUDENTS_CONSENT_FORM', $conn);
            $consentFormVersion = readSetting('STUDENTS_CONSENT_FORM_VERSION', $conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }    

        $this->view_PageData['subscriptionListInfos'] = $eventSubscriptionListInfosObj;
        $this->view_PageData['consentFormLink'] = $consentFormLink;
        $this->view_PageData['consentFormVersion'] = $consentFormVersion;
    }

    public function pre_viewsurveylist()
    {
        $this->title = "SisEPI - Evento: Pesquisas de satisfação";
		$this->subtitle = "Pesquisas de satisfação preenchidas";
		
		$this->moduleName = "SRVEY";
		$this->permissionIdRequired = 4;
    }

    public function viewsurveylist()
    {
        require_once("model/database/eventsurveys.database.php");
        require_once("controller/component/DataGrid.class.php");
        require_once("model/GenericObjectFromDataRow.class.php");

        $eventId = isset($_GET['eventId']) && isId($_GET['eventId']) ? $_GET['eventId'] : null;
        $surveyList = null;
        $eventInfos = null;

        $surveyListTransformRules =
        [
            'ID' => fn($row) => $row['id'],
            'Data de envio' => fn($row) => date_create($row['registrationDate'])->format('d/m/Y H:i:s')
        ];

        $dataGridComponent = new DataGridComponent();
        $dataGridComponent->RudButtonsFunctionParamName = "ID";
        $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("events3", "viewsinglesurvey", "{param}");
		$dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("events3", "deletesinglesurvey", "{param}");

        $conn = createConnectionAsEditor();
        try
        {
            $surveyList = getAllAnsweredSurveysOfEvent($eventId, $conn);
            $eventInfos = new GenericObjectFromDataRow(getEventBasicInfos($eventId, $conn));
            $dataGridComponent->dataRows = Data\transformDataRows($surveyList, $surveyListTransformRules);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['eventInfos'] = $eventInfos;
        $this->view_PageData['surveyList'] = $surveyList;
        $this->view_PageData['dgComp'] = $dataGridComponent;
    }

    public function pre_viewsinglesurvey()
    {
        $this->title = "SisEPI - Ver pesquisa de satisfação";
		$this->subtitle = "Ver pesquisa de satisfação";
		
		$this->moduleName = "SRVEY";
		$this->permissionIdRequired = 4;
    }

    public function viewsinglesurvey()
    {
        require_once ("model/AnsweredEventSurvey.php");
        require_once("model/database/eventsurveys.database.php");
        require_once("model/GenericObjectFromDataRow.class.php");

        $surveyId = !empty($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
        $surveyDataRowObj = null;
        $surveyObj = null;
        try
        {
            $surveyDataRowObj = new GenericObjectFromDataRow(getSingleAnsweredSurvey($surveyId));
            $surveyObj = new AnsweredEventSurvey(json_decode($surveyDataRowObj->surveyJson));
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }

        $this->view_PageData['surveyDataRowObj'] = $surveyDataRowObj;
        $this->view_PageData['surveyObj'] = $surveyObj;
    }

    public function pre_deletesinglesurvey()
    {
        $this->title = "SisEPI - Excluir pesquisa de satisfação";
		$this->subtitle = "Excluir pesquisa de satisfação";
		
		$this->moduleName = "SRVEY";
		$this->permissionIdRequired = 5;
    }

    public function deletesinglesurvey()
    {
        require_once("model/database/eventsurveys.database.php");
        require_once("model/GenericObjectFromDataRow.class.php");

        $surveyId = !empty($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
        $surveyDataRowObj = null;
        try
        {
            $surveyDataRowObj = new GenericObjectFromDataRow(getSingleAnsweredSurvey($surveyId));
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }

        $this->view_PageData['surveyDataRowObj'] = $surveyDataRowObj;
    }

}