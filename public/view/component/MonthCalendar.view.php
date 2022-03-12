<?php
function drawDay($dtDay, $eventsDates)
{
	$getEventsOfTheDay = function($dtDay) use ($eventsDates)
	{
		return array_filter($eventsDates, function($event) use ($dtDay)
		{
			return $event['date'] === $dtDay->format("Y-m-d");
		});
	};

    $writeDayNumberStyle = function($dtDay)
    {
        return 'dayNumber' . ($dtDay->format("Y-m-d") === date("Y-m-d") ? ' today' : '');
    };

	echo '<td>';
	if (isset($dtDay))
	{
		echo '<a href="' . URL\URLGenerator::generateSystemURL("calendar", "viewday", null, [ 'day' => $dtDay->format("Y-m-d") ] ) . '">';
		echo '<span class="' . $writeDayNumberStyle($dtDay) . '">' . $dtDay->format("d") . '</span>';
		
		foreach ($getEventsOfTheDay($dtDay) as $event)
			switch($event['type'])
			{
				case 'holiday':
					echo '<div class="dayInfoBox holiday">' . truncateText($event['name'], 40) . '</div>';
					break;
				case 'event':
					echo '<div class="dayInfoBox event">' . truncateText($event['name'], 40) . '</div>';
					break;
                case 'publicsimpleevent':
                case 'privatesimpleevent':
                    echo '<div class="dayInfoBox simpleevent">' . truncateText($event['name'], 40) . '</div>';
                    break;
                default: 
                    echo '<div class="dayInfoBox">' . truncateText($event['name'], 40) . '</div>';
                    break;
			}
		
		echo '</a>';
	}
	echo '</a></td>';
}

function drawWeek($weekArray, $eventsDates)
{
	echo "<tr>";
	foreach ($weekArray as $day)
		drawDay($day, $eventsDates);
	echo "</tr>";
}

function drawMonth($monthArray, $eventsDates)
{
	foreach ($monthArray as $week)
		drawWeek($week, $eventsDates);
}

function createMonthArray($refDateTime)
{
	$monthArray = [];
	$dateTimeInfo = clone $refDateTime;
	$monthNumber = (int)$dateTimeInfo->format("m");
	$lastDay = (int)$dateTimeInfo->format("t");
	
	do 
	{
		$preWeekArray = array_fill(0, 7, '');
		$weekArray = array_map( function($key, $value) use ($monthNumber, &$dateTimeInfo)
		{
			$ret = null;
			if ((int)$dateTimeInfo->format("w") === $key && (int)$dateTimeInfo->format("m") === $monthNumber)
			{
				$ret = clone $dateTimeInfo;
				$dateTimeInfo->modify("+1 day");
			}
			return $ret;
		}, array_keys($preWeekArray), $preWeekArray);
		
		$monthArray[] = $weekArray;
	}
	while ($dateTimeInfo->format("m") === $refDateTime->format("m"));
		
	return $monthArray;
}
?>

<style>
    table.monthCalendar
    {
        margin-top: 1em;
        margin-bottom: 1em;
        border-collapse: collapse;
        cursor: pointer;
    }
    
    .monthCalendar td
    {
        min-height: 128px;
        vertical-align: top;
        border: 1px solid lightgray;
        padding: 5px;
    }
    
    .monthCalendar td a 
    { 
        display: block; 
        width: 100%;
        height: 100%;
        text-decoration: none;
    }
    
    .monthCalendar td:hover
    {
        background-color: lightgray;
    }
    
    .dayNumber
    {
        font-weight: bold;
        font-size: 1.5em;
        position: relative;
        color: #22B14C;
    }

    .dayNumber.today::after
    {
        position: absolute;
        display: block;
        left: -0.2em;
        top: -0.1em;
        width: 1.2em;
        height: 1.2em;
        border-radius: 50%;
        border: 3px solid red;
        content: "";
    }
    
    .dayInfoBox
    {
        border: 1px solid darkgray;
        margin-bottom: 2px;
        padding: 2px;
        font-size: 0.8em;
    }
    
    .dayInfoBox.event { background-color: lightgreen; color: #22B14C}
    .dayInfoBox.holiday { background-color: pink; color: #cc0000; }
    .dayInfoBox.simpleevent { background-color: lightyellow; color: #aaaa00; }

    @media all and (min-width: 750px)
    {
        .monthCalendar td
        {
            width: 14%;
            height: 128px;
        }
    }
    
</style>

<?php if (isset($refDateTime, $eventsList)): ?>
    <table class="monthCalendar">
        <thead>
            <tr>
                <th>Domingo</th>
                <th>Segunda-feira</th>
                <th>Terça-feira</th>
                <th>Quarta-feira</th>
                <th>Quinta-feira</th>
                <th>Sexta-feira</th>
                <th>Sábado</th>
            </tr>
        </thead>
        <tbody>
            <?php drawMonth(createMonthArray($refDateTime), $eventsList);  ?>
        </tbody>
    </table>
<?php endif; ?>