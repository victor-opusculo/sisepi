<?php if (empty($_GET['messages'])): ?>
<form method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/calendar.createdate.post.php", "cont=calendar&action=createdate"); ?>" >
    <span class="formField"><label>Nome: <input type="text" name="calendardates:txtName" maxlength="120" required="required" size="60"/></label></span>
    <span class="formField"><label>Marcar para o dia: <input type="date" name="calendardates:dateEventDate" required="required" value="<?php echo $_GET['day'] ?? ''; ?>"/></label></span>
    <span class="formField">
        <label>Horário: <input type="time" step="1" name="calendardates:timeBeginTime"/></label> às 
        <label><input type="time" step="1" name="calendardates:timeEndTime"/></label> (opcional) </span>
    <span class="formField">
        <label>Descrição (opcional):
            <textarea name="calendardates:txtDescription" maxlength="278" style="width:100%;" rows="3"></textarea>
        </label>
    </span>
    <span class="formField">
        <span>Tipo: </span>
        <label style="display: block;"><input type="radio" name="calendardates:radType" value="holiday" required="required"/> Feriado/Ponto facultativo</label>
        <label style="display: block;"><input type="radio" name="calendardates:radType" value="privatesimpleevent" required="required" /> Evento simples não visível ao público</label>
        <label style="display: block;"><input type="radio" name="calendardates:radType" value="publicsimpleevent" required="required" /> Evento simples visível ao público</label>
    </span>
    <input type="submit" value="Criar" name="btnsubmitSubmitDate" />
</form>
<?php endif; ?>