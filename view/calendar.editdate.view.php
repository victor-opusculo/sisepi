
<?php if (isset($calendarEventObj)): 
    
    $writeTypeCheckStatus = function($currentRadioButtonValue) use ($calendarEventObj)
    {
        return $currentRadioButtonValue === $calendarEventObj->type ? 'checked="checked"' : '';
    };
    
    ?>
<form method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/calendar.editdate.post.php", "cont=calendar&action=editdate&id=" . $calendarEventObj->id); ?>" >
    <span class="formField"><label>Nome: <input type="text" name="txtName" maxlength="120" required="required" size="60" value="<?php echo $calendarEventObj->title; ?>"/></label></span>
    <span class="formField"><label>Marcar para o dia: <input type="date" name="dateEventDate" required="required" value="<?php echo $calendarEventObj->date; ?>"/></label></span>
    <span class="formField">
        <label>Horário: <input type="time" step="1" name="timeBeginTime" value="<?php echo $calendarEventObj->beginTime; ?>"/></label> às 
        <label><input type="time" step="1" name="timeEndTime" value="<?php echo $calendarEventObj->endTime; ?>"/></label> (opcional) </span>
    <span class="formField">
        <label>Descrição (opcional):
            <textarea name="txtDescription" maxlength="278" style="width:100%;" rows="3"><?php echo $calendarEventObj->description; ?></textarea>
        </label>
    </span>
    <span class="formField">
        <span>Tipo: </span>
        <label style="display: block;"><input type="radio" name="radType" value="holiday" required="required" <?php echo $writeTypeCheckStatus("holiday"); ?>/> Feriado/Ponto facultativo</label>
        <label style="display: block;"><input type="radio" name="radType" value="privatesimpleevent" required="required" <?php echo $writeTypeCheckStatus("privatesimpleevent"); ?> /> Evento simples não visível ao público</label>
        <label style="display: block;"><input type="radio" name="radType" value="publicsimpleevent" required="required" <?php echo $writeTypeCheckStatus("publicsimpleevent"); ?> /> Evento simples visível ao público</label>
    </span>
    <input type="hidden" name="calendarEventId" value="<?php echo $calendarEventObj->id; ?>" />
    <input type="submit" value="Editar" name="btnsubmitSubmitDate" />
</form>
<?php endif; ?>