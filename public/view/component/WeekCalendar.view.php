<style>
    #weekDaysFrame
    {
        padding: 10px 10px 70px 10px;
        border-radius: 20px;
        border: 1px solid lightgray;
        /*background: linear-gradient(0deg, #ddd 0%, #ededed 100%);*/
        position: relative;
        overflow: hidden;
        background-image:url(<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/notebookbackground.jpg'); ?>);
    }

    #weekDaysFrame > h4 { text-align: center; margin: 0.5em; }

    .weekday.last { padding-bottom: 95px; }

    .wcalDoubleColumnFrame h4
    {
        display: block;
        margin: 0;
    }

    .dayNumber
    {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 1.4rem;
        color: #22B14C;
    }
    
    .dayEventBox
    {
        border: 1px solid darkgray;
        margin-bottom: 8px;
        padding: 5px;
        font-size: 0.9rem;
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
        font-size: 1.2rem;
    }

    .dayEventBox .timeLabel
    {
        display: block;
        text-align: right;
        font-size: 1rem;
    }

    .locationText { font-size: 1rem; }
    
    .dayEventBox.event { background-color: lightgray; color: #888; }
    .dayEventBox.holiday { background-color: pink; color: #cc0000; }
    .dayEventBox.simpleevent { background-color: lightyellow; color: #aaaa00; }

    .weekDayCalendarFooterImage
    {
        position: absolute;
        background-image:url(<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/rodape-transp.png'); ?>);
        background-size: auto 70px;
	    background-repeat: repeat-x;
        bottom: 0;
        left: 0;
        right: 0;
	    height: 70px;
    }

    @media all and (min-width: 749px)
    {
        .wcalLogos
        {
            position: absolute;
            bottom: 70px;
            left: 50%;
            right: 0;
        }

        .wcalDoubleColumnFrame
        {
            column-count: 2;
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
                echo '<div style="break-inside: avoid-column;">'; 
                echo '<h4>';
                switch ($dt->format('w'))
                {
                    case 0: echo 'Domingo'; break;
                    case 1: echo 'Segunda-feira'; break;
                    case 2: echo 'Terça-feira'; break;
                    case 3: echo 'Quarta-feira'; break;
                    case 4: echo 'Quinta-feira'; break;
                    case 5: echo 'Sexta-feira'; break;
                    case 6: echo 'Sábado'; break;
                }
                echo '</h4>';
                echo '<span class="dayNumber">' . $dt->format('d') . '</span>';
                $dayEvents = array_filter($eventsList, fn($ev) => $ev['date'] === $dt->format('Y-m-d') );
                $closedAvoidBreakDiv = false;
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
                    <?php if (!$closedAvoidBreakDiv)
                    {
                        echo '</div>';
                        $closedAvoidBreakDiv = true;
                    }?>
                <?php 
                }

                if (!$closedAvoidBreakDiv)
                {
                    echo '</div>';
                    $closedAvoidBreakDiv = true;
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
        <div class="wcalDoubleColumnFrame">
            <div class="weekday">
                <?php $writeDay($sundayDateTime); ?>
            </div>
            <div class="weekday">
                <?php $writeDay($monday); ?>
            </div>
            <div class="weekday">
                <?php $writeDay($tuesday); ?>
            </div>
            <div class="weekday">
                <?php $writeDay($wednesday); ?>
            </div>
            <div class="weekday">
                <?php $writeDay($thursday); ?>
            </div>
            <div class="weekday">
                <?php $writeDay($friday); ?>  
            </div>
            <div class="weekday last">
                <?php $writeDay($saturdayDateTime); ?>
            </div>
            <div class="centControl wcalLogos">
                <img src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/EPI.png'); ?>" height="90" style="margin-right: 50px;" />
                <img src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/CMI.png'); ?>" height="60" />
            </div>
        </div>

        <div class="weekDayCalendarFooterImage">
        </div>
    </div>

<?php endif; ?>