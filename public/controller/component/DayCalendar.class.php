<?php
//public
class DayCalendarComponent extends ComponentBase
{
	protected $name = "DayCalendar";
    private $dateTime;
    private $eventsList;

    public function __construct(string $date, array $eventsList)
    {
        parent::__construct();
        $this->dateTime = new DateTime($date);
        $this->eventsList = $eventsList;
    }

    public function render()
    {
        $dateTime = $this->dateTime;
        $eventsList = $this->eventsList;

        $view = $this->get_view();
		require_once($view);
    }

    public function getDateTime()
    {
        return $this->dateTime;
    }
}