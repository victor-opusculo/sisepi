<?php

require_once("model/GenericObjectFromDataRow.class.php");
require_once "model/Database/database.php";
require_once "vendor/autoload.php";

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
        require_once("model/Database/certificates.database.php");

        $eventId = isset($_GET['eventId']) && isId($_GET['eventId']) ? $_GET['eventId'] : null;
        $certsDataGrid = new DataGridComponent();
        $eventObj = null;
        $certsDataRows = null;
        $certsCount = 0;
		
		$transformDataRowsRules =
		[
			'id' => fn($row) => $row['id'],
			'Nome' => fn($row) => $row['name'],
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
        require_once("model/Database/students.database.php");
        require_once("model/Database/generalsettings.database.php");

        $eventId = isset($_GET['eventId']) && isId($_GET['eventId']) ? $_GET['eventId'] : null;

        $eventSubscriptionListInfosObj = null;
        $subscriptionTemplateObject = null;

        $conn = createConnectionAsEditor();
        try
        {
            $eventSubscriptionListInfosDataRow = getEventSubscriptionListInfos($eventId, $conn);
            if ($eventSubscriptionListInfosDataRow === null)
                throw new Exception("Registro não localizado");

            $eventSubscriptionListInfosObj = new GenericObjectFromDataRow($eventSubscriptionListInfosDataRow);
            if (!(bool)$eventSubscriptionListInfosObj->subscriptionListNeeded)
                throw new Exception("Este evento não usa lista de inscrição");

            $subscriptionTemplateObject = json_decode(getEventsSubscriptionTemplate($eventId, $conn));
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }   

        $this->view_PageData['subscriptionListInfos'] = $eventSubscriptionListInfosObj;
        $this->view_PageData['subscriptionTemplateObj'] = $subscriptionTemplateObject;
        $this->view_PageData['connection'] = $conn;
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
        require_once("model/Database/eventsurveys.database.php");
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
        require_once("model/Database/eventsurveys.database.php");
        require_once("model/GenericObjectFromDataRow.class.php");

        $surveyId = !empty($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
        $surveyDataRowObj = null;
        $surveyObj = null;
        try
        {
            $surveyDataRowObj = new GenericObjectFromDataRow(getSingleAnsweredSurvey($surveyId));
            $surveyObj = new \SisEpi\Model\AnsweredEventSurvey(json_decode($surveyDataRowObj->surveyJson));
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
        require_once("model/Database/eventsurveys.database.php");
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

    public function pre_listsubscriptions()
	{
		$this->title = "SisEPI - Inscrições";
		$this->subtitle = "Inscrições";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 4;
	}

    public function listsubscriptions()
    {
        require_once "controller/component/DataGrid.class.php";
        require_once "controller/component/Paginator.class.php";

        $dataGridComponent = null;
        $paginatorComponent = null;

        $conn = createConnectionAsEditor();
        try
        {
            $getter = new \SisEpi\Model\Events\EventSubscription();
            $getter->setCryptKey(getCryptoKey());

            $paginatorComponent = new PaginatorComponent($getter->getCount($conn, $_GET['q'] ?? ''), 30);
            
            $subs = $getter->getMultiplePartially($conn, $paginatorComponent->pageNum, $paginatorComponent->numResultsOnPage, $_GET['orderBy'] ?? '', $_GET['q'] ?? '');

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($subs, 
            [
                'id' => fn($s) => $s->id,
                'eventId' => fn($s) => $s->eventId,
                'Nome' => function($s)
                {
                    $socialName = $s->getQuestionAnswer('socialName');
					$accessRequired = $s->getQuestionAnswer('accessibilityRequired');

					return $s->name . (!empty($socialName) ? ' (' . $socialName . ')' : '') . (!empty($accessRequired) ? ' ♿' : '');
                },
                'E-mail' => fn($s) => $s->email,
                'Evento' => fn($s) => $s->getOtherProperties()->eventName,
                'Data de inscrição' => fn($s) => date_create($s->subscriptionDate)->format('d/m/Y H:i:s')
            ]));

            $dataGridComponent->columnsToHide = [ 'id', 'eventId' ];
            $dataGridComponent->RudButtonsFunctionParamName = 'id';
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('events2', 'viewsubscription', '{param}');
            $dataGridComponent->customButtons['Ver evento'] = URL\URLGenerator::generateSystemURL('events', 'view', '{eventId}');
            $dataGridComponent->customButtonsParameters['eventId'] = 'eventId';
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['dgComp'] = $dataGridComponent;
        $this->view_PageData['pagComp'] = $paginatorComponent;
    }

    public function pre_listcertificates()
    {
        $this->title = "SisEPI - Ver certificados emitidos";
		$this->subtitle = "Ver certificados emitidos";
		
		$this->moduleName = "EVENT";
		$this->permissionIdRequired = 11;
    }

    public function listcertificates()
    {
        require_once "controller/component/DataGrid.class.php";
        require_once "controller/component/Paginator.class.php";

        $dataGridComponent = null;
        $paginatorComponent = null;

        $conn = createConnectionAsEditor();
        try
        {
            $getter = new \SisEpi\Model\Events\EventCertificate();
            $getter->setCryptKey(getCryptoKey());

            $paginatorComponent = new PaginatorComponent($getter->getCount($conn, $_GET['q'] ?? ''), 20);
            
            $certs = $getter->getMultiplePartially($conn, $paginatorComponent->pageNum, $paginatorComponent->numResultsOnPage, $_GET['orderBy'] ?? '', $_GET['q'] ?? '');

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($certs, 
            [
                'ID' => fn($c) => $c->id,
                'eventId' => fn($c) => $c->eventId,
                'Nome' => fn($c) => $c->getOtherProperties()->name,
                'E-mail' => fn($c) => $c->email,
                'Evento' => fn($c) => $c->getOtherProperties()->eventName,
                'Data de emissão' => fn($c) => date_create($c->dateTime)->format('d/m/Y H:i:s')
            ]));
            $dataGridComponent->columnsToHide = [ 'eventId' ];
            $dataGridComponent->customButtons['Ver evento'] = URL\URLGenerator::generateSystemURL('events', 'view', '{eventId}');
            $dataGridComponent->customButtonsParameters['eventId'] = 'eventId';
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['dgComp'] = $dataGridComponent;
        $this->view_PageData['pagComp'] = $paginatorComponent;
    }

}