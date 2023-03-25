
<?php if (isset($calendarEventObj)): 
    
    $writeTypeCheckStatus = function($currentRadioButtonValue) use ($calendarEventObj)
    {
        return $currentRadioButtonValue === $calendarEventObj->type ? 'checked="checked"' : '';
    };
    
    ?>

<script>
    const popupURL = '<?= URL\URLGenerator::generatePopupURL('{popup}') ?>';
    const getTraitInfosScript = '<?= URL\URLGenerator::generateFileURL('generate/getTraitInfos.php') ?>';
    const traitIconsPath = '<?= URL\URLGenerator::generateFileURL('uploads/traits/') ?>';
</script>
<script src="<?php echo URL\URLGenerator::generateFileURL("view/calendar.editdate.view.js"); ?>"></script>

<!-- Page Elements templates -->

<span id="spanNewDate" style="display: none;" class="formField extraDate">
    <label>Marcar para o dia: <input type="date" name="extra:dateEventDate" required="required" value="<?php echo $_GET['day'] ?? ''; ?>"/></label>
    <label>Horário: <input type="time" step="1" name="extra:timeBeginTime"/></label> às 
    <label><input type="time" step="1" name="extra:timeEndTime"/></label> (opcional) 
    <button type="button" style="min-width: 30px;" class="btnRemoveExtraDate">&times;</button>
</span>
<table style="display: none;">
    <tr id="newTraitTr">
        <td>
            <img class="dateTrait" src="" alt="" title="" height="32" data-traitId=""/>
        </td>
        <td class="shrinkCell">
            <button type="button" class="btnTraitRemove" style="min-width: 20px;">&times;</button>
        </td>
    </tr>
</table>

<!--END Page Elements templates -->

<form id="frmCreateDate" method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/calendar.editdate.post.php", "cont=calendar&action=editdate&id=" . $calendarEventObj->id); ?>" >
    <?php if (!empty($calendarEventObj->parentId)): ?>    
        <p style="color: red;">Este evento/data está vinculado a <a href="<?php echo URL\URLGenerator::generateSystemURL("calendar", "editdate", $calendarEventObj->parentId); ?>">outra data</a>. Não deseja alterar a data mestre?</p> 
    <?php endif; ?>
    <span class="formField"><label>Nome: <input type="text" name="calendardates:txtName" maxlength="120" required="required" size="60" value="<?php echo $calendarEventObj->title; ?>"/></label></span>
    <div id="datesList">
        <span class="formField">
            <label>Marcar para o dia: <input type="date" name="calendardates:dateEventDate" required="required" value="<?php echo $calendarEventObj->date; ?>"/></label>
            <label>Horário: <input type="time" step="1" name="calendardates:timeBeginTime" value="<?php echo $calendarEventObj->beginTime; ?>"/></label> às 
            <label><input type="time" step="1" name="calendardates:timeEndTime" value="<?php echo $calendarEventObj->endTime; ?>"/></label> (opcional) 
        </span>
        <?php if (isset($calendarEventObj->childDates)): ?>
            <?php foreach ($calendarEventObj->childDates as $child): ?>
            <span id="spanNewDate" data-id="<?php echo $child->id; ?>" class="formField extraDate">
                <label>Marcar para o dia: <input type="date" name="extra:dateEventDate" required="required" value="<?php echo $child->date; ?>"/></label>
                <label>Horário: <input type="time" step="1" name="extra:timeBeginTime" value="<?php echo $child->beginTime; ?>"/></label> às 
                <label><input type="time" step="1" name="extra:timeEndTime" value="<?php echo $child->endTime; ?>"/></label> (opcional) 
                <button type="button" style="min-width: 30px;" class="btnRemoveExtraDate">&times;</button>
            </span>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" style="min-width: 30px;" id="btnAddDate">+</button>
    <span class="formField">
        <label>Descrição (opcional):
            <textarea name="calendardates:txtDescription" maxlength="278" style="width:100%;" rows="3"><?php echo $calendarEventObj->description; ?></textarea>
        </label>
    </span>
    <span class="formField">
        <span>Tipo: </span>
        <label style="display: block;"><input type="radio" name="calendardates:radType" value="holiday" required="required" <?php echo $writeTypeCheckStatus("holiday"); ?>/> Feriado/Ponto facultativo</label>
        <label style="display: block;"><input type="radio" name="calendardates:radType" value="privatesimpleevent" required="required" <?php echo $writeTypeCheckStatus("privatesimpleevent"); ?> /> Evento simples não visível ao público</label>
        <label style="display: block;"><input type="radio" name="calendardates:radType" value="publicsimpleevent" required="required" <?php echo $writeTypeCheckStatus("publicsimpleevent"); ?> /> Evento simples visível ao público</label>
    </span>
    <span class="formField"><label><input type="checkbox" id="chkSetCustomStyle" onchange="document.getElementById('fieldStyleCustomization').style.display = this.checked ? 'inherit' : 'none';" <?php echo !empty($calendarEventObj->styleJson) ? ' checked="checked" ' : ''; ?>/> Customizar aparência na agenda</label></span>
    <span class="formField" id="fieldStyleCustomization" style="display: <?php echo empty($calendarEventObj->styleJson) ? 'none' : 'inherit'; ?>;">
        <ul>
            <?php $styleObj = json_decode($calendarEventObj->styleJson); ?>
            <li><label>Cor de fundo: <input type="color" id="colorStyleBgColor" value="<?php echo $styleObj->backgroundColor ?? '#000000'; ?>"/></label></li>
            <li><label>Cor do texto: <input type="color" id="colorStyleTextColor" value="<?php echo $styleObj->textColor ?? '#000000'; ?>" /></label></li>
        </ul>
    </span>

    <span class="searchFormField">
        <label>Traços: <input type="number" min="1" step="1" id="numAddTraitId" style="width: 50px;" /></label>
        <button type="button" id="btnAddTraitId" style="min-width: 20px;"><?= hsc('+') ?></button>
        <button type="button" id="btnTraitSearch" style="min-width: 20px;"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
    </span>
    <table>
        <tbody id="tbodyDateTraits">
            <?php foreach ($calendarEventObj->traits as $trait): ?>
                <tr>
                    <td>
                        <img class="dateTrait" src="<?= URL\URLGenerator::generateFileURL("uploads/traits/{$trait->id}.{$trait->fileExtension}") ?>" height="32" alt="<?= $trait->name ?>" title="<?= $trait->name ?>" data-traitId="<?= $trait->id ?>"/>
                    </td>
                    <td class="shrinkCell">
                        <button type="button" class="btnTraitRemove" style="min-width: 20px;">&times;</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" id="customStyleJson" name="calendardates:styleJson" />
    <input type="hidden" name="calendardates:calendarEventId" value="<?php echo $calendarEventObj->id; ?>" />
    <input type="hidden" name="calendardates:calendarParentId" value="<?php echo $calendarEventObj->parentId; ?>" />
    <input type="hidden" id="masterDateTraits" name="dateTraits" />
    <input type="hidden" id="extraDatesChangesReport" name="extraDatesChangesReport" value="" />
    <input type="submit" value="Editar" id="btnsubmitSubmitDate" name="btnsubmitSubmitDate" />
</form>
<?php endif; ?>