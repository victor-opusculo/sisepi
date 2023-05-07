<?php

use SisEpi\Model\Database\Connection;

require_once "vendor/autoload.php";

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
            ['Nome' => 'Soma de horas acumuladas de participantes de acordo com resposa de campo da inscrição', 'action' => 'eventsubscriptionhoursbyquestionvalue' ],
            ['Nome' => 'Período da agenda detalhado', 'action' => 'calendarperiodreport' ],
            ['Nome' => 'Relações ODS por ano de exercício', 'action' => 'odsrelationsperiodreport' ],
            ['Nome' => 'Logs do sistema', 'action' => 'systemlogs' ]
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

        $this->moduleName = "SRVEY";
		$this->permissionIdRequired = 4;
    }

    public function eventsurveysreport()
    {
        require_once "model/Reports/EventSurveyReport.php";
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

        $this->moduleName = "EVENT";
		$this->permissionIdRequired = 4;
    }

    public function eventsubscriptions()
    {
        require_once "model/Reports/EventSubscriptionReport.php";
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

        $this->moduleName = "EVENT";
		$this->permissionIdRequired = 4;
    }

    public function eventsubscriptionhoursbyquestionvalue()
    {
        require_once "model/Reports/EventSubscribersHoursBySubsValueReport.php";
        require_once "model/Database/enums.settings.database.php";

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

    public function pre_calendarperiodreport()
    {
        $this->title = "SisEPI - Relatório: Período da agenda detalhado";
		$this->subtitle = "Relatório: Período da agenda detalhado";

        $this->moduleName = "CALEN";
		$this->permissionIdRequired = 1;
    }

    public function calendarperiodreport()
    {
        $reportObject = null;

        $conn = Connection::get();
        try
        {
            $from = $_GET['begin'] ?? '';
            $to = $_GET['end'] ?? '';

            if (!empty($from) && !empty($to))
            {
                $reportObject = new \SisEpi\Model\Reports\CalendarPeriodReport($conn, $from, $to, true);
            }
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['reportObj'] = $reportObject;
    }

    public function pre_odsrelationsperiodreport()
    {
        $this->title = "SisEPI - Relatório: Relações ODS em determinado exercício";
		$this->subtitle = "Relatório: Relações ODS em determinado exercício";

        $this->moduleName = "ODSRL";
		$this->permissionIdRequired = 1;
    }

    public function odsrelationsperiodreport()
    {
        $reportObject = null;
        $year = isset($_GET['year']) && is_numeric($_GET['year']) ? $_GET['year'] : null;

        $conn = Connection::get();
        try
        {
            if ($year !== null)
                $reportObject = new \SisEpi\Model\Reports\OdsRelationsPeriodReport($year, $conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['reportObj'] = $reportObject;

    }

    public function pre_systemlogs()
    {
        $this->title = "SisEPI - Relatório: Logs do sistema";
		$this->subtitle = "Relatório: Logs do sistema";

        $this->moduleName = "LOG";
		$this->permissionIdRequired = 1;
    }

    public function systemlogs()
    {
        require_once "controller/component/DataGrid.class.php";

        $dataGridComponent = null;
        $availableLogFiles = glob(SISEPI_BASEDIR . "/log/*.log");;
        try
        {
            if (!empty($_GET['file']))
            {
                $fileToLoad = SISEPI_BASEDIR . "/log/" . $_GET['file'];

                if (!file_exists($fileToLoad)) 
                    throw new Exception("Arquivo de log especificado não existe.");

                $logContents = file_get_contents($fileToLoad);
                $lines = explode(PHP_EOL, $logContents);
                $linesAndCells = array_map( fn($line) => explode(" | ", $line), $lines);
                array_pop($linesAndCells);

                $dataGridComponent = new DataGridComponent(Data\transformDataRows($linesAndCells, 
                [
                    'Data e hora' => fn($l) => $l[0],
                    'IP' => fn($l) => mb_ereg_replace("IP: ", "", $l[1]),
                    'Usuário' => fn($l) => mb_ereg_replace("Usuário: ", "", $l[2]),
                    'Ação' => fn($l) => $l[3]
                ]));
            }
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }

        $this->view_PageData['dgComp'] = $dataGridComponent;
        $this->view_PageData['availableFiles'] = $availableLogFiles;
    }
}