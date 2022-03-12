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

    .dayEventBox .editDeleteLinks a { color: unset; }
    
    .dayEventBox.event { background-color: lightgreen; color: #22B14C; }
    .dayEventBox.holiday { background-color: pink; color: #cc0000; }
    .dayEventBox.simpleevent { background-color: lightyellow; color: #aaaa00; }
</style>

<?php if (isset($dateTime, $eventsList)): ?>

    <div id="dayEventsListBox">
        <span class="dayNumber"><?php echo $dateTime->format("d/m/Y"); ?></span>
        <?php
            $eventsListFiltered = array_filter($eventsList, fn($event) => $event['date'] === $dateTime->format("Y-m-d") );

            $generateBoxStyleClassName = function($event)
            {
                switch ($event['type'])
                {
                    case 'event': return 'dayEventBox event';
                    case 'holiday': return 'dayEventBox holiday';
                    case 'publicsimpleevent': 
                    case 'privatesimpleevent': return 'dayEventBox simpleevent';
                    default: return 'dayEventBox';
                }
            }; 

            if (count($eventsListFiltered) > 0): 
                foreach ($eventsListFiltered as $event): ?>
                    <div class="<?php echo $generateBoxStyleClassName($event); ?>">
                        <a href="<?php echo $event['onViewClickURL']; ?>">
                            <span class="title"><?php echo $event['title']; ?></span>
                            <div><?php echo $event['description']; ?></div>
                            <?php if (!empty($event['beginTime']) && !empty($event['endTime'])): ?>
                                <span class="timeLabel">
                                    <?php echo $event['beginTime'] . ' - ' . $event['endTime']; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <?php if (!empty($event['onEditClickURL']) && !empty($event['onDeleteClickURL'])): ?>
                            <span class="editDeleteLinks">
                                (<a href="<?php echo $event['onEditClickURL']; ?>">Editar</a> | 
                                <a href="<?php echo $event['onDeleteClickURL']; ?>">Excluir</a>)
                            </span>
                        <?php endif; ?>
                    </div>
                
        <?php   endforeach; 
            else: ?>
            <p>Não há datas ou eventos para o dia selecionado.</p>
        <?php endif; ?>

    </div>

<?php endif; ?>