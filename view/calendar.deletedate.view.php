
<?php if (isset($calendarEventObj)): ?>
<form method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/calendar.deletedate.post.php", "cont=calendar&action=home"); ?>" >
    <p style="text-align: center;">Deseja realmente excluir este evento/data? Esta operação é irreversível!</p>
    
    <div class="viewDataFrame">
        <label>Nome: </label><?php echo $calendarEventObj->title; ?><br/>
        <label>Data: </label><?php echo date_format(date_create($calendarEventObj->date), "d/m/Y"); ?><br/>
        <label>Descrição: </label><?php echo nl2br($calendarEventObj->description); ?><br/>
        <label>Horário: </label><?php echo $calendarEventObj->beginTime . " - " . $calendarEventObj->endTime; ?>
    </div>
    
    <div class="centControl">
        <input type="submit" value="Sim, excluir" name="btnsubmitDelete" />
        <input type="button" value="Não excluir" onclick="history.back();" />
    </div>
    <input type="hidden" name="calendardates:calendarEventId" value="<?php echo $calendarEventObj->id; ?>" />
</form>
<?php endif; ?>