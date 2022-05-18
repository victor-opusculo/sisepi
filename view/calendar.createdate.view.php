<?php if (empty($_GET['messages'])): ?>

<script src="<?php echo URL\URLGenerator::generateFileURL("view/calendar.editdate.view.js"); ?>"></script>
<span id="spanNewDate" style="display: none;" class="formField extraDate">
    <label>Marcar para o dia: <input type="date" name="extra:dateEventDate" required="required" value="<?php echo $_GET['day'] ?? ''; ?>"/></label>
    <label>Horário: <input type="time" step="1" name="extra:timeBeginTime"/></label> às 
    <label><input type="time" step="1" name="extra:timeEndTime"/></label> (opcional) 
    <button type="button" style="min-width: 30px;" class="btnRemoveExtraDate">&times;</button>
</span>

<form id="frmCreateDate" method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/calendar.createdate.post.php", "cont=calendar&action=createdate"); ?>" >
    <span class="formField"><label>Nome: <input type="text" name="calendardates:txtName" maxlength="120" required="required" size="60"/></label></span>
    <div id="datesList">
        <span class="formField">
            <label>Marcar para o dia: <input type="date" name="calendardates:dateEventDate" required="required" value="<?php echo $_GET['day'] ?? ''; ?>"/></label>
            <label>Horário: <input type="time" step="1" name="calendardates:timeBeginTime"/></label> às 
            <label><input type="time" step="1" name="calendardates:timeEndTime"/></label> (opcional) 
        </span>
    </div>
    <button type="button" style="min-width: 30px;" id="btnAddDate">+</button>
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
    <span class="formField"><label><input type="checkbox" id="chkSetCustomStyle" onchange="document.getElementById('fieldStyleCustomization').style.display = this.checked ? 'inherit' : 'none';" /> Customizar aparência na agenda</label></span>
    <span class="formField" id="fieldStyleCustomization" style="display: none;">
        <ul>
            <li><label>Cor de fundo: <input type="color" id="colorStyleBgColor" /></label></li>
            <li><label>Cor do texto: <input type="color" id="colorStyleTextColor" /></label></li>
        </ul>
    </span>
    <input type="hidden" id="customStyleJson" name="calendardates:styleJson" />
    <input type="hidden" id="extraDatesChangesReport" name="extra:extraDatesChangesReport" value="" />
    <input type="submit" value="Criar" id="btnsubmitSubmitDate" name="btnsubmitSubmitDate" />
</form>
<?php endif; ?>