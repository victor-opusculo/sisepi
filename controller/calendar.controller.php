<?php
//private
require_once("model/database/calendar.database.php");

final class calendar extends BaseController
{
    public function pre_home()
	{
		$this->title = "SisEPI - Agenda";
		$this->subtitle = "Agenda";

        $this->moduleName = "CALEN";
		$this->permissionIdRequired = 1;
	}

    public function home()
    {
        require_once("component/MonthCalendar.class.php");
        
        $eventsListTransformRules =
        [
            'date' => fn($row) => $row['date'],
            'name' => fn($row) => $row['eventName'] . " - " . $row['name'],
            'type' => fn($row) => 'event'
        ];

        $calendarEventsListTransformRules =
        [
            'date' => fn($row) => $row['date'],
            'name' => fn($row) => $row['title'],
            'type' => fn($row) => $row['type']
        ];

        $month = !empty($_GET["month"]) ? $_GET["month"] : date("n");
	    $year = !empty($_GET["year"]) ? $_GET["year"] : date("Y");

        $conn = createConnectionAsEditor();
        $monthCalendarComponent = null;
        try
        {
            $eventsList = Data\transformDataRows(getCertifiableEventsInMonth($month, $year, $conn), $eventsListTransformRules);
            $calendarEventsList = Data\transformDataRows(getCalendarEventsInMonth($month, $year, $conn), $calendarEventsListTransformRules);
            
            $monthCalendarComponent = new MonthCalendarComponent($month, $year, [...$eventsList, ...$calendarEventsList] );
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['monthsList'] = MonthCalendarComponent::generateMonthsList();
        $this->view_PageData['mcalComp'] = $monthCalendarComponent;
    }

    public function pre_viewday()
    {
        $this->title = "SisEPI - Dia da agenda";
		$this->subtitle = "Dia da agenda";

        $this->moduleName = "CALEN";
		$this->permissionIdRequired = 2;
    }

    public function viewday()
    {
        require_once("component/DayCalendar.class.php");

        $eventsListTransformRules =
        [
            'date' => fn($row) => $row['date'],
            'title' => fn($row) => $row['eventName'],
            'description' => fn($row) => $row['name'],
            'beginTime' => fn($row) => $row['beginTime'],
            'endTime' => fn($row) => $row['endTime'],
            'onViewClickURL' => fn($row) => URL\URLGenerator::generateSystemURL("events", "view", $row['eventId']),
            'type' => fn($row) => 'event'
        ];

        $calendarEventsListTransformRules =
        [
            'date' => fn($row) => $row['date'],
            'title' => fn($row) => $row['title'],
            'description' => fn($row) => $row['description'],
            'beginTime' => fn($row) => $row['beginTime'],
            'endTime' => fn($row) => $row['endTime'],
            'onViewClickURL' => fn($row) => "#",
            'onEditClickURL' => fn($row) => URL\URLGenerator::generateSystemURL("calendar", "editdate", $row['id']),
            'onDeleteClickURL' => fn($row) => URL\URLGenerator::generateSystemURL("calendar", "deletedate", $row['id']),
            'type' => fn($row) => $row['type']
        ];

        $selectedDate = !empty($_GET['day']) ? $_GET['day'] : date('d-m-Y');
        $dayCalendarComponent = null;

        $conn = createConnectionAsEditor();
        try
        {
            $eventsList = Data\transformDataRows(getCertifiableEventsInDay($selectedDate, $conn), $eventsListTransformRules);
            $calendarEventsList = Data\transformDataRows(getCalendarEventsInDay($selectedDate, $conn), $calendarEventsListTransformRules);

            $dayCalendarComponent = new DayCalendarComponent($selectedDate, [...$calendarEventsList, ...$eventsList] );
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['dcalComp'] = $dayCalendarComponent;
    }

    public function pre_createdate()
    {
        $this->title = "SisEPI - Agenda: Criar data/evento";
		$this->subtitle = "Criar data/evento da agenda";

        $this->moduleName = "CALEN";
		$this->permissionIdRequired = 3;
    }

    public function createdate()
    {
    }

    public function pre_editdate()
    {
        $this->title = "SisEPI - Agenda: Editar data/evento";
		$this->subtitle = "Editar data/evento da agenda";

        $this->moduleName = "CALEN";
		$this->permissionIdRequired = 4;
    }

    public function editdate()
    {
        require_once("model/GenericObjectFromDataRow.class.php");

        $calendarEventId = !empty($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : 0;

        $calendarEventObject = null;
        try
        {
            $calendarEventObject = new GenericObjectFromDataRow(getSingleCalendarEvent($calendarEventId));
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = "Registro não localizado.";
            $this->pageMessages[] = $e->getMessage();
        }

        $this->view_PageData['calendarEventObj'] = $calendarEventObject;
    }

    public function pre_deletedate()
    {
        $this->title = "SisEPI - Agenda: Excluir data/evento";
		$this->subtitle = "Excluir data/evento da agenda";

        $this->moduleName = "CALEN";
		$this->permissionIdRequired = 5;
    }

    public function deletedate()
    {
        $this->editdate();
    }

}