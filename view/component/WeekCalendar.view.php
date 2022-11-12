<style>
    #weekDaysFrame
    {
        padding: 10px 10px 10px 10px;
        border-radius: 20px;
        border: 1px solid lightgray;
        /*background: linear-gradient(0deg, #ddd 0%, #ededed 100%);*/
        position: relative;
        overflow: hidden;
        background-color: #386044;
    }

    #weekDaysFrame > h4 { text-align: center; margin: 0.5em; color: #fff; font-family: sans-serif; }

    .weekday.last { padding-bottom: 95px; }

    .dayNumber
    {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 1.4rem;
        color: #22B14C;
		text-align: center;
    }
    
    .dayEventBox
    {
        border: 1px solid darkgray;
        margin-bottom: 8px;
        padding: 5px;
        font-size: 0.8rem;
        break-inside: avoid-column;
    }

    .dayEventBox > a
    {
        display: block; 
        width: 100%;
        height: 100%;
        text-decoration: none;
        color: unset;
    }

    .dayEventBox .title
    {
        display: block;
        border-bottom: 1px solid darkgray;
        font-size: 1.05rem;
    }

    .dayEventBox .timeLabel
    {
        display: block;
        text-align: right;
        font-size: 0.8rem;
    }

    .locationText { font-size: 0.8rem; }
    
    .dayEventBox.event { background-color: lightgray; color: #888; }
    .dayEventBox.holiday { background-color: pink; color: #cc0000; }
    .dayEventBox.simpleevent { background-color: lightyellow; color: #aaaa00; }
	
	.weekDaysTable td, .weekDaysTable th
	{
		padding-left: 5px;
		vertical-align: top;
		border-bottom: none;
	}
	
	.weekDaysTable tr { border: none; }
	
	.weekDaysTable
	{
		border-collapse: separate;
		border-radius: 10px;
		border: 1px solid darkgray;
		background-color: #fafafa;
	}
	
	.wcalLogos
	{
		text-align: center;
	}
</style>

<?php if (isset($referenceDateTime, $eventsList)): ?>

    <?php
            $writeDayEventBoxDivStyle = function($event)
            {
                $class = 'class="dayEventBox ';
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

            $writeDay = function($dt) use ($eventsList, $writeDayEventBoxDivStyle)
            {
                echo '<span class="dayNumber">' . $dt->format('d') . '</span>';
                $dayEvents = array_filter($eventsList, fn($ev) => $ev['date'] === $dt->format('Y-m-d') );
                foreach ($dayEvents as $event) 
                {
                ?>
                    <div <?php echo $writeDayEventBoxDivStyle($event); ?> >
                        <a href="<?php echo $event['onViewClickURL']; ?>">
                            <span class="title"><?php echo $event['title']; ?></span>
                            <span><?php echo truncateText($event['description'], 40); ?></span>
                            <?php if (!empty($event['beginTime']) && !empty($event['endTime'])): ?>
                                <span class="timeLabel"><?php echo $event['beginTime'] . ' às ' . $event['endTime']; ?></span>
                            <?php endif; ?>
                            <?php if (!empty($event['location'])): ?>
                                <span class="locationText"><strong>Local: </strong><?php echo $event['location']; ?></span>
                            <?php endif; ?>
                            <?php if (!empty($event['traits'])): ?>
                                <div class="rightControl">
                                <?php foreach ($event['traits'] as $trait): ?>
                                    <img 
                                        src="<?= URL\URLGenerator::generateFileURL("uploads/traits/{$trait->id}.{$trait->fileExtension}") ?>"
                                        alt="<?= $trait->name ?>"
                                        title="<?= $trait->name . ': ' . $trait->description ?>"
                                        height="24" />
                                <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php 
                }
            };

            $needleDt = clone $sundayDateTime;
            $monday = clone $needleDt->modify('+1 day');
            $tuesday = clone $needleDt->modify('+1 day');
            $wednesday = clone $needleDt->modify('+1 day');
            $thursday = clone $needleDt->modify('+1 day');
            $friday = clone $needleDt->modify('+1 day');
?>
    
    <div id="weekDaysFrame">
        <h4>Eventos da semana do dia <?php echo $sundayDateTime->format('d/m/Y') . ' ao dia ' . $saturdayDateTime->format('d/m/Y'); ?></h4>
        <table class="weekDaysTable">
			<thead>
				<tr>
					<th>Domingo</th>
					<th>Segunda</th>
					<th>Terça</th>
					<th>Quarta</th>
					<th>Quinta</th>
					<th>Sexta</th>
					<th>Sábado</th>
				</tr>
			</thead>
			<tbody>
                <tr>
                    <td>
                        <?php $writeDay($sundayDateTime); ?>
                    </td>
                    <td>
                        <?php $writeDay($monday); ?>
                    </td>
                    <td>
                        <?php $writeDay($tuesday); ?>
                    </td>
                    <td>
                        <?php $writeDay($wednesday); ?>
                    </td>
                    <td>
                        <?php $writeDay($thursday); ?>
                    </td>
                    <td>
                        <?php $writeDay($friday); ?>
                    </td>
                    <td>
                        <?php $writeDay($saturdayDateTime); ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="wcalLogos">
			<img src="<?php echo URL\URLGenerator::generateFileURL('pics/epi_white.png'); ?>" height="80"/>
			<img src="<?php echo URL\URLGenerator::generateFileURL('pics/cmi_white.png'); ?>" height="50" style="margin-left: 20px;"/>
		</div>
    </div>
<?php endif; ?>