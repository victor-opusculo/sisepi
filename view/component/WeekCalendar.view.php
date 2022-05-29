<style>
    #weekDaysFrame
    {
        padding: 10px 10px 70px 10px;
        border-radius: 20px;
        border: 1px solid darkgray;
        background: linear-gradient(0deg, #ddd 0%, #ededed 100%);
        position: relative;
        overflow: hidden;
    }

    #weekDaysFrame > h4 { text-align: center; margin: 0.5em; }

    .weekDaysHalfFrame h4
    {
        display: block;
        margin: 0;
    }

    .dayNumber
    {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 1.2em;
        color: #22B14C;
    }
    
    .dayEventBox
    {
        border: 1px solid darkgray;
        margin-bottom: 10px;
        padding: 5px;
        font-size: 0.8em;
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
        font-size: 0.9em;
    }

    .dayEventBox .timeLabel
    {
        display: block;
        text-align: right;
        font-size: 0.7em;
    }

    .locationText { font-size: 0.7em; }
    
    .dayEventBox.event { background-color: lightgray; color: #888; }
    .dayEventBox.holiday { background-color: pink; color: #cc0000; }
    .dayEventBox.simpleevent { background-color: lightyellow; color: #aaaa00; }

    .weekDayCalendarFooterImage
    {
        position: absolute;
        background-image:url(<?php echo URL\URLGenerator::generateFileURL('pics/rodape-transp.png'); ?>);
        background-size: auto 70px;
	    background-repeat: repeat-x;
        bottom: 0;
        left: 0;
        right: 0;
	    height: 70px;
    }

    @media all and (min-width: 749px)
    {
        #weekDaysFrameFlexbox { display: flex; }
        .weekDaysHalfFrame
        {
            flex: 50%;
            padding: 5px;
            position: relative;
        }

        .wdframe2 { padding-bottom: 90px; }

        .wcalLogos
        {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }
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
        <div id="weekDaysFrameFlexbox">
            <div class="weekDaysHalfFrame">
                <div id="weekdaySunday">
                    <h4>Domingo</h4>
                    <?php $writeDay($sundayDateTime); ?>
                </div>
                <div id="weekdayMonday">
                    <h4>Segunda-feira</h4>
                    <?php $writeDay($monday); ?>
                </div>
                <div id="weekdayTuesday">
                    <h4>Terça-feira</h4>
                    <?php $writeDay($tuesday); ?>
                </div>
                <div id="weekdayWednesday">
                    <h4>Quarta-feira</h4>
                    <?php $writeDay($wednesday); ?>
                </div>
            </div>
            <div class="weekDaysHalfFrame wdframe2">
                <div id="weekdayThursday">
                    <h4>Quinta-feira</h4>
                    <?php $writeDay($thursday); ?>
                </div>
                <div id="weekdayFriday">
                    <h4>Sexta-feira</h4>
                    <?php $writeDay($friday); ?>  
                </div>
                <div id="weekdaySaturday">
                    <h4>Sábado</h4>
                    <?php $writeDay($saturdayDateTime); ?>
                </div>
                <div class="centControl wcalLogos">
                    <img src="<?php echo URL\URLGenerator::generateFileURL('pics/EPI.png'); ?>" height="90" style="margin-right: 50px;" />
                    <img src="<?php echo URL\URLGenerator::generateFileURL('pics/CMI.png'); ?>" height="60" />
                </div>
            </div>
        </div>

        <div class="weekDayCalendarFooterImage">
        </div>
    </div>

<?php endif; ?>