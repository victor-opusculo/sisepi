<?php
//public
require_once("model/database/calendar.database.php");
require_once "model/traits/EntityTrait.php";

final class calendar extends BaseController
{
    public function pre_home()
	{
		$this->title = "SisEPI - Agenda";
		$this->subtitle = "Agenda";
	}

    public function home()
    {
        require_once("component/MonthCalendar.class.php");
        
        $eventsListTransformRules =
        [
            'date' => fn($row) => $row['date'],
            'name' => fn($row) => $row['eventName'] . " - " . $row['name'],
            'type' => fn($row) => 'event',
			'beginTime' => fn($row) => $row['beginTime'],
            'style' => fn($row) => json_decode($row['calendarInfoBoxStyleJson'] ?? null)
        ];

        $calendarEventsListTransformRules =
        [
            'date' => fn($row) => $row['date'],
            'name' => fn($row) => $row['title'],
            'type' => fn($row) => $row['type'],
			'beginTime' => fn($row) => $row['beginTime'],
            'style' => fn($row) => json_decode($row['styleJson'] ?? null)
        ];

        $month = !empty($_GET["month"]) ? $_GET["month"] : date("n");
	    $year = !empty($_GET["year"]) ? $_GET["year"] : date("Y");

        $conn = createConnectionAsEditor();
        $monthCalendarComponent = null;
        try
        {
            $eventsList = Data\transformDataRows(getCertifiableEventsInMonth($month, $year, $conn), $eventsListTransformRules);
            $calendarEventsList = Data\transformDataRows(getCalendarEventsInMonth($month, $year, $conn), $calendarEventsListTransformRules);
            
			$fullEventsList = [...$eventsList, ...$calendarEventsList];
			usort($fullEventsList, 'calendarCompareDateTimeFromEventsList');
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
    }

    public function viewday()
    {
        require_once("component/DayCalendar.class.php");

        $conn = createConnectionAsEditor();

        $traitsGetter = new \Model\Traits\EntityTrait();
        $eventsListTransformRules =
        [
            'date' => fn($row) => $row['date'],
            'title' => fn($row) => $row['eventName'],
            'description' => fn($row) => $row['name'],
            'beginTime' => fn($row) => $row['beginTime'],
            'endTime' => fn($row) => $row['endTime'],
            'onViewClickURL' => fn($row) => URL\URLGenerator::generateSystemURL("events", "view", $row['eventId']),
            'type' => fn($row) => 'event',
            'style' => fn($row) => json_decode($row['calendarInfoBoxStyleJson'] ?? null),
            'location' => fn($row) => $row['locationName'],
            'traits' => fn($row) => $traitsGetter->getCertifiableEventTraits($conn, $row['eventDateId'])
        ];

        $calendarEventsListTransformRules =
        [
            'date' => fn($row) => $row['date'],
            'title' => fn($row) => $row['title'],
            'description' => fn($row) => $row['description'],
            'beginTime' => fn($row) => $row['beginTime'],
            'endTime' => fn($row) => $row['endTime'],
            'onViewClickURL' => fn($row) => "#",
            'style' => fn($row) => json_decode($row['styleJson'] ?? null),
            'type' => fn($row) => $row['type'],
            'traits' => fn($row) => $traitsGetter->getCalendarEventTraits($conn, $row['id'])
        ];

        $selectedDate = !empty($_GET['day']) ? $_GET['day'] : date('d-m-Y');
        $dayCalendarComponent = null;

        try
        {
            $eventsList = Data\transformDataRows(getCertifiableEventsInDay($selectedDate, $conn), $eventsListTransformRules);
            $calendarEventsList = Data\transformDataRows(getCalendarEventsInDay($selectedDate, $conn), $calendarEventsListTransformRules);

			$fullEventsList = [...$calendarEventsList, ...$eventsList];
			usort($fullEventsList, 'calendarCompareDateTimeFromEventsList');

            $dayCalendarComponent = new DayCalendarComponent($selectedDate, $fullEventsList);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['dcalComp'] = $dayCalendarComponent;
    }

    public function pre_viewweek()
    {
        $this->title = "SisEPI - Semana da agenda";
		$this->subtitle = "Semana da agenda";
    }

    public function viewweek()
    {
        require_once("component/WeekCalendar.class.php");

        $conn = createConnectionAsEditor();
        $traitsGetter = new \Model\Traits\EntityTrait();
        $eventsListTransformRules =
        [
            'date' => fn($row) => $row['date'],
            'title' => fn($row) => $row['eventName'],
            'description' => fn($row) => $row['name'],
            'beginTime' => fn($row) => $row['beginTime'],
            'endTime' => fn($row) => $row['endTime'],
            'onViewClickURL' => fn($row) => URL\URLGenerator::generateSystemURL("events", "view", $row['eventId']),
            'type' => fn($row) => 'event',
            'style' => fn($row) => json_decode($row['calendarInfoBoxStyleJson'] ?? null),
            'location' => fn($row) => $row['locationName'] ?? null,
            'traits' => fn($row) => $traitsGetter->getCertifiableEventTraits($conn, $row['eventDateId'])
        ];

        $calendarEventsListTransformRules =
        [
            'date' => fn($row) => $row['date'],
            'title' => fn($row) => $row['title'],
            'description' => fn($row) => $row['description'],
            'beginTime' => fn($row) => $row['beginTime'],
            'endTime' => fn($row) => $row['endTime'],
            'onViewClickURL' => fn($row) => "#",
            'style' => fn($row) => json_decode($row['styleJson'] ?? null),
            'type' => fn($row) => $row['type'],
            'traits' => fn($row) => $traitsGetter->getCalendarEventTraits($conn, $row['id'])
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
            for ($currentDt = clone $weeksSunday; $currentDt <= $weeksSaturday; $currentDt->modify('+1 day'))
            {
                $eventsList = [...$eventsList, ...Data\transformDataRows(getCertifiableEventsInDay($currentDt->format('Y-m-d'), $conn), $eventsListTransformRules)];
                $calendarEventsList = [...$calendarEventsList, ...Data\transformDataRows(getCalendarEventsInDay($currentDt->format('Y-m-d'), $conn), $calendarEventsListTransformRules)];
            }

			$fullEventsList = [...$calendarEventsList, ...$eventsList];
			usort($fullEventsList, 'calendarCompareDateTimeFromEventsList');

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

function calendarCompareDateTimeFromEventsList($a, $b)
{
	$dt1 = new DateTime($a['date'] . ' ' . $a['beginTime']);
	$dt2 = new DateTime($b['date'] . ' ' . $b['beginTime']);
	
	return $dt1 <=> $dt2;
}