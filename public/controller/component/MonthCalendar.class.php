<?php
//public
class MonthCalendarComponent extends ComponentBase
{
	protected $name = "MonthCalendar";
    private $refDateTime;
    private $eventsList;

    public function __construct(int $month, int $year, array $eventsList)
    {
        parent::__construct();
        $this->refDateTime = new DateTime("$year-$month-01");
        $this->eventsList = $eventsList;
    }

    public function render()
    {
        $refDateTime = $this->refDateTime;
        $eventsList = $this->eventsList;

        $view = $this->get_view();
		require_once($view);
    }

    public function getReferenceDateTime()
    {
        return $this->refDateTime;
    }

    public static function generateMonthsList()
    {
        $format = new IntlDateFormatter('pt_BR', IntlDateFormatter::NONE, 
              IntlDateFormatter::NONE, NULL, NULL, "MMMM");

        $generateMonthsNumbers = function()
        {
            for ($m = 1; $m <= 12; $m++)
                yield $m;
        };

        return array_map( fn($key) => datefmt_format($format, mktime(0, 0, 0, $key)), [...$generateMonthsNumbers()] );
    }
}