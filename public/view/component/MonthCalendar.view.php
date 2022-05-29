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

    $writeInfoBoxDivStyle = function($event)
    {
        $class = 'class="dayInfoBox ';
        $style = '';
        
        if (empty($event['style']))
        {
            switch($event['type'])
			{
                case 'holiday': $class .= 'holiday'; break;
                case 'event': $class .= 'event'; break;
                case 'publicsimpleevent':
                case 'privatesimpleevent': $class .= 'simpleevent'; break;
                default: break;
            }
        }
        else
        {
            $style = 'style="';
            $style .= 'background-color: ' . $event['style']->backgroundColor . ';';
            $style .= 'color: ' . $event['style']->textColor . ';';
            $style .= '"';
        }
        $class .= '"';

        return " $class $style ";
    };

	echo '<td>';
	if (isset($dtDay))
	{
		echo '<div class="dayBox">';
		echo '<span class="' . $writeDayNumberStyle($dtDay) . '">' . $dtDay->format("d") . '</span>';
		
		foreach ($getEventsOfTheDay($dtDay) as $event)
            echo '<div ' . $writeInfoBoxDivStyle($event) . '>' . hsc(truncateText($event['name'], 40)) . '</div>';
		
		echo '<a href="' . URL\URLGenerator::generateSystemURL("calendar", "viewday", null, [ 'day' => $dtDay->format("Y-m-d") ] ) . '">';
		echo '</a>';
		echo '</div>';
		
	}
	echo '</td>';
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
		height: 100%;
    }
    
    .monthCalendar td
    {
        height: 100%;
        vertical-align: top;
        border: 1px solid lightgray;
        padding: 5px;
        transition: background-color 0.5s;
    }
    
	.monthCalendar tbody tr { height: 100%; }
	
	.monthCalendar .dayBox
	{
		position: relative;
		min-height: 130px;
		height: 100%;
	}
	
    .monthCalendar td .dayBox a 
    { 
		position:absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
        display: block; 
        text-decoration: none;
    }
    
    .monthCalendar td:hover
    {
        background-color: #eee;
        transition: background-color 0.5s;
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
    
    .dayInfoBox.event { background-color: lightgray; color: #888; }
    .dayInfoBox.holiday { background-color: pink; color: #cc0000; }
    .dayInfoBox.simpleevent { background-color: lightyellow; color: #aaaa00; }

    @media all and (min-width: 750px)
    {
        .monthCalendar td { width: 14%; }
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