<style>
    #dayEventsListBox
    {
        display: block;
        padding: 10px;
    }

    .dayNumber
    {
        display: block;
        border-bottom: 2px solid black;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 1.5em;
        color: #22B14C;
    }
    
    .dayEventBox
    {
        border: 1px solid darkgray;
        margin-bottom: 10px;
        padding: 5px;
        font-size: 1em;
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
        font-size: 1.2em;
    }

    .dayEventBox .timeLabel, .dayEventBox .editDeleteLinks
    {
        display: block;
        text-align: right;
        font-size: 0.9em;
    }

    .locationText { font-size: 0.8em; }

    .dayEventBox .editDeleteLinks a { color: unset; }
    
    .dayEventBox.event { background-color: lightgray; color: #888; }
    .dayEventBox.holiday { background-color: pink; color: #cc0000; }
    .dayEventBox.simpleevent { background-color: lightyellow; color: #aaaa00; }
</style>

<?php if (isset($dateTime, $eventsList)): ?>

    <div id="dayEventsListBox">
        <span class="dayNumber"><?php echo $dateTime->format("d/m/Y"); ?></span>
        <?php
            $eventsListFiltered = array_filter($eventsList, fn($event) => $event['date'] === $dateTime->format("Y-m-d") );

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

            if (count($eventsListFiltered) > 0): 
                foreach ($eventsListFiltered as $event): ?>
                    <div <?php echo $writeDayEventBoxDivStyle($event); ?>>
                        <a href="<?php echo hscq($event['onViewClickURL']); ?>">
                            <span class="title"><?php echo hsc($event['title']); ?></span>
                            <div><?php echo hsc($event['description']); ?></div>
                            <?php if (!empty($event['beginTime']) && !empty($event['endTime'])): ?>
                                <span class="timeLabel">
                                    <?php echo hsc($event['beginTime']) . ' - ' . hsc($event['endTime']); ?>
                                </span>
                            <?php endif; ?>
                            <?php if (!empty($event['location'])): ?>
                                <div class="locationText">
                                    <strong>Local: </strong><?php echo $event['location']; ?>
                                </div>
                            <?php endif; ?>
                        </a>
                        <?php if (!empty($event['onEditClickURL']) && !empty($event['onDeleteClickURL'])): ?>
                            <span class="editDeleteLinks">
                                (<a href="<?php echo hscq($event['onEditClickURL']); ?>">Editar</a> | 
                                <a href="<?php echo hscq($event['onDeleteClickURL']); ?>">Excluir</a>)
                            </span>
                        <?php endif; ?>
                    </div>
                
        <?php   endforeach; 
            else: ?>
            <p>Não há datas ou eventos para o dia selecionado.</p>
        <?php endif; ?>

    </div>

<?php endif; ?>