<?php if (isset($eventOrEventDateInfos))
{ ?>
    <div class="viewDataFrame">
    <?php switch ($eventOrEventDateInfos['_entityType'])
    {
        case 'event': ?>
            <label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('events', 'view', $eventOrEventDateInfos['id']); ?>"><?php echo $eventOrEventDateInfos['name']; ?></a>
        <?php break;
        case 'eventdate': ?>
            <label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('events', 'view', $eventOrEventDateInfos['eventId']); ?>"><?php echo $eventOrEventDateInfos['eventName']; ?></a>
            <br/>
            <label>Nome/Conteúdo: </label><?php echo $eventOrEventDateInfos['eventDateName']; ?><br/>
            <label>Data: </label><?php echo (new DateTime($eventOrEventDateInfos['date']))->format('d/m/Y'); ?><br/>
            <label>Horário: </label><?php echo $eventOrEventDateInfos['beginTime'] . ' - ' . $eventOrEventDateInfos['endTime']; ?><br/>
        <?php break;
    } ?>
    </div>
<?php } ?>

<?php if ($createForm): ?>
<script>
    function btnsubmitSubmitEventChecklist_onClick(e)
    {
        if (generateChecklistJson)
            document.getElementById("eventchecklistsJson").value = JSON.stringify(generateChecklistJson());
    }
</script>
<form action="<?php echo URL\URLGenerator::generateFileURL('post/eventchecklists.edit.post.php', 'cont=eventchecklists&action=edit&id=' . $checklistDataObject->id); ?>" method="post">
<?php endif; ?>

<?php if (isset($checklistDataObject)) 
    $editChecklistPage->render(); 
else
    echo '<p>Nenhum checklist definido.</p>';
    ?>

    
<?php if ($createForm): ?>
    <input type="hidden" name="eventchecklists:checklistId" value="<?php echo $checklistDataObject->id; ?>"/>
    <input type="hidden" id="eventchecklistsJson" name="eventchecklists:checklistJson" />
    <div class="centControl">
        <input type="submit" value="Salvar alterações" name="btnsubmitSubmitEventChecklist" onclick="btnsubmitSubmitEventChecklist_onClick(event)"/>
    </div>
</form>
<?php endif; ?>