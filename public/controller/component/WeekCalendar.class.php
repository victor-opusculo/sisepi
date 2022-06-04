<?php
//public
class WeekCalendarComponent extends ComponentBase
{
	protected $name = "WeekCalendar";
    private $referenceDateTime;
    private $weeksSundayDateTime;
    private $weeksSaturdayDateTime;

    private $eventsList;

    public function __construct(string $referenceDate, array $eventsList)
    {
        parent::__construct();
        $this->referenceDateTime = new DateTime($referenceDate);

        $this->weeksSundayDateTime = new DateTime($referenceDate);
        $this->weeksSaturdayDateTime = new DateTime($referenceDate);

        if ($this->weeksSundayDateTime->format('w') > 0) $this->weeksSundayDateTime->modify('last sunday');
        if ($this->weeksSaturdayDateTime->format('w') < 6) $this->weeksSaturdayDateTime->modify('next saturday');

        $this->eventsList = $eventsList;
    }

    public function render()
    {
        $referenceDateTime = $this->referenceDateTime;
        $sundayDateTime = $this->weeksSundayDateTime;
        $saturdayDateTime = $this->weeksSaturdayDateTime;

        $eventsList = $this->eventsList;
        
        $view = $this->get_view();
		require_once($view);
    }

    public function getReferenceDateTime() { return $this->referenceDateTime; }

    public function getSundayDateTime() { return $this->weeksSundayDateTime; }

    public function getSaturdayDateTime() { return $this->weeksSaturdayDateTime; }
}