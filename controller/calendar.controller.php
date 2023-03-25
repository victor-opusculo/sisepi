<?php
//private
require_once("model/Database/calendar.database.php");
require_once "vendor/autoload.php";

use \SisEpi\Model\Database\Connection;
use \SisEpi\Model\Calendar\CalendarDate;
use \SisEpi\Model\Events\EventDate;

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
            'date' => fn($row) => $row->date,
            'name' => fn($row) => $row->getOtherProperties()->eventName . " - " . $row->name,
            'type' => fn($row) => 'event',
			'beginTime' => fn($row) => $row->beginTime,
            'style' => fn($row) => json_decode($row->getOtherProperties()->calendarInfoBoxStyleJson ?? '')
        ];

        $calendarEventsListTransformRules =
        [
            'date' => fn($row) => $row->date,
            'name' => fn($row) => $row->title,
            'type' => fn($row) => $row->type,
			'beginTime' => fn($row) => $row->beginTime,
            'style' => fn($row) => json_decode($row->styleJson ?? '')
        ];

        $month = !empty($_GET["month"]) ? (int)$_GET["month"] : (int)date("n");
	    $year = !empty($_GET["year"]) ? (int)$_GET["year"] : (int)date("Y");

        $conn = \SisEpi\Model\Database\Connection::get();
        $monthCalendarComponent = null;
        try
        {
            $eventDatesGetter = new \SisEpi\Model\Events\EventDate();
            $eventsList = Data\transformDataRows($eventDatesGetter->getAllFromMonth($conn, $month, $year), $eventsListTransformRules);

            $calendarDatesGetter = new \SisEpi\Model\Calendar\CalendarDate();
            $calendarEventsList = Data\transformDataRows($calendarDatesGetter->getAllFromMonth($conn, $month, $year), $calendarEventsListTransformRules);
            
			$fullEventsList = [...$eventsList, ...$calendarEventsList];
			
			usort($fullEventsList, CalendarDate::class . '::calendarCompareDateTimeFromEventsList');
            $monthCalendarComponent = new MonthCalendarComponent($month, $year, $fullEventsList);
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

        $conn = Connection::get();
        $traitsGetter = new \SisEpi\Model\Traits\EntityTrait();
        $eventsListTransformRules =
        [
            'date' => fn($row) => $row->date,
            'title' => fn($row) => $row->getOtherProperties()->eventName,
            'description' => fn($row) => $row->name,
            'beginTime' => fn($row) => $row->beginTime,
            'endTime' => fn($row) => $row->endTime,
            'onViewClickURL' => fn($row) => URL\URLGenerator::generateSystemURL("events", "view", $row->eventId),
            'type' => fn($row) => 'event',
            'style' => fn($row) => json_decode($row->getOtherProperties()->calendarInfoBoxStyleJson ?? ''),
            'location' => fn($row) => $row->getOtherProperties()->locationName ?? '',
            'traits' => fn($row) => $traitsGetter->getCertifiableEventTraits($conn, $row->eventDateId)
        ];

        $calendarEventsListTransformRules =
        [
            'date' => fn($row) => $row->date,
            'title' => fn($row) => $row->title,
            'description' => fn($row) => $row->description,
            'beginTime' => fn($row) => $row->beginTime,
            'endTime' => fn($row) => $row->endTime,
            'onViewClickURL' => fn($row) => "#",
            'onEditClickURL' => fn($row) => URL\URLGenerator::generateSystemURL("calendar", "editdate", $row->id),
            'onDeleteClickURL' => fn($row) => URL\URLGenerator::generateSystemURL("calendar", "deletedate", $row->id),
            'style' => fn($row) => json_decode($row->styleJson ?? ''),
            'type' => fn($row) => $row->type,
            'traits' => fn($row) => $traitsGetter->getCalendarEventTraits($conn, $row->id)
        ];

        $selectedDate = !empty($_GET['day']) ? $_GET['day'] : date('Y-m-d');
        $dayCalendarComponent = null;

        try
        {
            $eventDatesGetter = new EventDate();
            $eventsList = Data\transformDataRows($eventDatesGetter->getAllFromDay($conn, $selectedDate), $eventsListTransformRules);

            $calendarDatesGetter = new CalendarDate();
            $calendarEventsList = Data\transformDataRows($calendarDatesGetter->getAllFromDay($conn, $selectedDate), $calendarEventsListTransformRules);

			$fullEventsList = [...$calendarEventsList, ...$eventsList];
			usort($fullEventsList, CalendarDate::class . '::calendarCompareDateTimeFromEventsList');

            $dayCalendarComponent = new DayCalendarComponent($selectedDate, $fullEventsList);
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
        $calendarEventId = !empty($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : 0;

        $calendarEventObject = null;
        $conn = Connection::get();
        try
        {
            $getter = new CalendarDate();
            $getter->id = $calendarEventId;
            $calendarEventObject = $getter->getSingle($conn);
            $calendarEventObject->fetchChildDates($conn);
            $calendarEventObject->fetchTraits($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = "Registro não localizado.";
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

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

    public function pre_viewweek()
    {
        $this->title = "SisEPI - Semana da agenda";
		$this->subtitle = "Semana da agenda";

        $this->moduleName = "CALEN";
		$this->permissionIdRequired = 2;
    }

    public function viewweek()
    {
        require_once("component/WeekCalendar.class.php");

        $conn =  Connection::get();

        $eventsListTransformRules =
        [
            'date' => fn($row) => $row->date,
            'title' => fn($row) => $row->getOtherProperties()->eventName,
            'description' => fn($row) => $row->name,
            'beginTime' => fn($row) => $row->beginTime,
            'endTime' => fn($row) => $row->endTime,
            'onViewClickURL' => fn($row) => URL\URLGenerator::generateSystemURL("events", "view", $row->eventId),
            'type' => fn($row) => 'event',
            'style' => fn($row) => json_decode($row->calendarInfoBoxStyleJson ?? ''),
            'location' => fn($row) => $row->getOtherProperties()->locationName ?? null,
            'traits' => fn($row) => $row->traits
        ];

        $calendarEventsListTransformRules =
        [
            'date' => fn($row) => $row->date,
            'title' => fn($row) => $row->title,
            'description' => fn($row) => $row->description,
            'beginTime' => fn($row) => $row->beginTime,
            'endTime' => fn($row) => $row->endTime,
            'onViewClickURL' => fn($row) => "#",
            'style' => fn($row) => json_decode($row->styleJson ?? ''),
            'type' => fn($row) => $row->type,
            'traits' => fn($row) => $row->traits
        ];

        $selectedDate = !empty($_GET['day']) ? $_GET['day'] : date('d-m-Y');
        $weekCalendarComponent = null;

        $weeksSunday = new DateTime($selectedDate);
        $weeksSaturday = new DateTime($selectedDate);

        if ($weeksSunday->format('w') > 0) $weeksSunday->modify('last sunday');
        if ($weeksSaturday->format('w') < 6) $weeksSaturday->modify('next saturday');

        $eventsList = [];
        $calendarEventsList = [];

        try
        {
            $eventDatesGetter = new EventDate();
            $calendarDatesGetter = new CalendarDate();
            for ($currentDt = clone $weeksSunday; $currentDt <= $weeksSaturday; $currentDt->modify('+1 day'))
            {
                $eventDatesToday = $eventDatesGetter->getAllFromDay($conn, $currentDt->format('Y-m-d'));
                array_walk($eventDatesToday, fn($ed) => $ed->fetchTraits($conn));
                $eventsList = [...$eventsList, ...Data\transformDataRows($eventDatesToday, $eventsListTransformRules)];

                $calendarDatesToday = $calendarDatesGetter->getAllFromDay($conn, $currentDt->format('Y-m-d'));
                array_walk($calendarDatesToday, fn($cd) => $cd->fetchTraits($conn));
                $calendarEventsList = [...$calendarEventsList, ...Data\transformDataRows($calendarDatesToday, $calendarEventsListTransformRules)];
            }

			$fullEventsList = [...$calendarEventsList, ...$eventsList];
			usort($fullEventsList, CalendarDate::class . '::calendarCompareDateTimeFromEventsList');

            $weekCalendarComponent = new WeekCalendarComponent($selectedDate, $fullEventsList);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['wcalComp'] = $weekCalendarComponent;
    }

}