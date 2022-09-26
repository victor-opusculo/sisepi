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
            ['Nome' => 'Pesquisas de satisfação de eventos', 'action' => 'eventsurveysreport' ]
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
}