<?php

final class reports extends BaseController
{
    public function pre_home()
    {
        $this->title = "SisEPI - Relatórios";
		$this->subtitle = "Relatórios";
    }

    public function home()
    {
        require_once "controller/component/DataGrid.class.php";

        $availableReports =
        [
            ['Nome' => 'Pesquisas de satisfação de eventos', 'action' => 'eventsurveysreport' ],
            ['Nome' => 'Inscrições de eventos', 'action' => 'eventsubscriptions' ],
            ['Nome' => 'Soma de horas acumuladas de participantes de acordo com resposa de campo da inscrição', 'action' => 'eventsubscriptionhoursbyquestionvalue' ]
        ];

        $dataGridComponent = new DataGridComponent($availableReports);
        $dataGridComponent->columnsToHide[] = 'action';
        $dataGridComponent->customButtons['Visualizar'] = URL\URLGenerator::generateSystemURL('reports', '{reportaction}', null);
        $dataGridComponent->customButtonsParameters['reportaction'] = 'action';

        $this->view_PageData['dgComp'] = $dataGridComponent;
    }

    public function pre_eventsurveysreport()
    {
        $this->title = "SisEPI - Relatório: Pesquisas de satisfação de eventos";
		$this->subtitle = "Relatório: Pesquisas de satisfação de eventos";
    }

    public function eventsurveysreport()
    {
        require_once "model/reports/EventSurveyReport.php";
        require_once "model/GenericObjectFromDataRow.class.php";

        $reportObject = null;
        $loadedEvents = null;
        $conn = createConnectionAsEditor();
        try
        {
            if (isset($_GET['eventIds']))
            {
                $reportObject = new EventSurveyReport($_GET['eventIds'], $conn);
                $loadedEvents = array_map( fn($row) => new GenericObjectFromDataRow($row), getEventsArrayBasicInfos($_GET['eventIds'], $conn));
            }
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['reportObj'] = $reportObject;
        $this->view_PageData['loadedEvents'] = $loadedEvents;
    }

    public function pre_eventsubscriptions()
    {
        $this->title = "SisEPI - Relatório: Inscrições de eventos";
		$this->subtitle = "Relatório: Inscrições de eventos";
    }

    public function eventsubscriptions()
    {
        require_once "model/reports/EventSubscriptionReport.php";
        require_once "model/GenericObjectFromDataRow.class.php";

        $reportObject = null;
        $loadedEvents = null;
        $conn = createConnectionAsEditor();
        try
        {
            if (isset($_GET['eid']))
            {
                $reportObject = new EventSubscriptionReport($_GET['eid'], $conn);
                $loadedEvents = array_map( fn($row) => new GenericObjectFromDataRow($row), getEventsArrayBasicInfos($_GET['eid'], $conn));
            }
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['reportObj'] = $reportObject;
        $this->view_PageData['loadedEvents'] = $loadedEvents;
    }

    public function pre_eventsubscriptionhoursbyquestionvalue()
    {
        $this->title = "SisEPI - Relatório: Horas acumuladas de inscritos por resposta de campo de inscrição";
		$this->subtitle = "Relatório: Horas acumuladas de inscritos por resposta de campo de inscrição";
    }

    public function eventsubscriptionhoursbyquestionvalue()
    {
        require_once "model/reports/EventSubscribersHoursBySubsValueReport.php";
        require_once "model/database/enums.settings.database.php";

        $reportObject = null;
        $dbEnums = null;
        $conn = createConnectionAsEditor();
        try
        {
            $dbEnums =  
            [    
                ...getEnumValues('GENDER', $conn), 
                ...getEnumValues('OCCUPATION', $conn),
                ...getEnumValues('SCHOOLING', $conn),
                ...getEnumValues('NATION', $conn),
                ...getEnumValues('RACE', $conn),
                ...getEnumValues('UF', $conn)
            ];

            if (isset($_GET['questValue'], $_GET['begin'], $_GET['end']))
                $reportObject = new \SisEpi\Model\Reports\EventSubscribersHoursBySubsValueReport($conn, $_GET['questValue'], $_GET['begin'], $_GET['end']);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['reportObj'] = $reportObject;
        $this->view_PageData['enumsValues'] = $dbEnums;
    }
}