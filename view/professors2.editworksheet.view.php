<?php 

function writeSelectedStatus($property, $valueToLookFor)
{
    return (string)$property === (string)$valueToLookFor ? ' selected="selected" ' : '';
}

?>

<script>
    const getEventInfosScriptURL = '<?php echo URL\URLGenerator::generateFileURL('generate/getEventInfos.php'); ?>';
    const getProfessorInfosScriptURL = '<?php echo URL\URLGenerator::generateFileURL('generate/getProfessorInfos.php'); ?>';
    const popupURL = '<?php echo URL\URLGenerator::generatePopupURL("{popup}"); ?>';
</script>
<script src="<?php echo URL\URLGenerator::generateFileURL('view/professors2.editworksheet.view.js'); ?>"></script>

<form method="post" action="<?php echo URL\URLGenerator::generateFileURL('post/professors2.editworksheet.post.php', 'cont=professors2&action=editworksheet&workSheetId=' . $workSheetObject->id); ?>">
    <h3>Proposta de trabalho vinculada</h3>
    <div class="viewDataFrame">
        <label>Nome: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('professors2', 'viewworkproposal', $proposalObject->id); ?>">
        <?php echo hsc($proposalObject->name); ?></a> <br/>
        <label>Descrição: </label><?php echo nl2br(hsc($proposalObject->description)); ?>
        <input type="hidden" name="professorworksheets:professorWorkProposalId" value="<?php echo $proposalObject->id; ?>" />
    </div>

    <h3>Evento vinculado</h3>
    <span style="display: flex; align-items: center;">
        <label>Evento ID: 
            <input type="number" id="numEventId" name="professorworksheets:numEventId" min="1" step="1" value="<?php echo $workSheetObject->eventId; ?>" />
        </label>
        <button type="button" id="btnLoadEvent" style="min-width:20px;" ><?php echo htmlspecialchars(">"); ?></button>
		<button type="button" id="btnSearchEvent"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
    </span>
    <div class="viewDataFrame">
        <label>Nome: </label><span id="lblEventName"></span> <br/>
        <label>Tipo: </label><span id="lblEventType"></span>
    </div>

    <h3>Docente desta ficha</h3>
    <span style="display: flex; align-items: center;">
        <label>Docente ID: 
            <input type="number" id="numProfessorId" name="professorworksheets:numProfessorId" min="1" step="1" required="required" value="<?php echo $workSheetObject->professorId; ?>" />
        </label>
        <button type="button" id="btnLoadProfessor" style="min-width:20px;" ><?php echo htmlspecialchars(">"); ?></button>
		<button type="button" id="btnSearchProfessor"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
    </span>
    <div class="viewDataFrame">
        <label>Nome: </label><span id="lblProfessorName"></span> <br/>
        <label>E-mail: </label><span id="lblProfessorEmail"></span> <br/>
        <label>Escolaridade: </label><span id="lblProfessorSchoolingLevel"></span>
    </div>
    <span class="formField">
        <label>Função: 
            <select name="professorworksheets:selProfessorType">
                <?php foreach ($paymentInfosObj->professorTypes as $i => $pt): ?>
                    <option value="<?php echo $i; ?>" <?php echo writeSelectedStatus($workSheetObject->professorTypeId, $i); ?>><?php echo hsc($pt->name); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </span>

    <h3>Pagamento</h3>
    <span class="formField">
        <label>Nível: 
            <select id="selPaymentLevel">
                <?php foreach ($paymentInfosObj->paymentLevelTables as $ti => $pt): ?>
                    <optgroup label="<?php echo hscq($pt->tableName); ?>">
                        <?php foreach ($paymentInfosObj->paymentLevelTables[$ti]->levels as $li => $pl): ?>
                            <option value="<?php echo "$ti:$li"; ?>"
                            <?php echo writeSelectedStatus("{$workSheetObject->paymentTableId}:{$workSheetObject->paymentLevelId}", "$ti:$li"); ?>><?php echo hsc($pl->name . ' (Hora-aula: ' . formatDecimalToCurrency($pl->classTimeValue) . ')'); ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
        </label>
    </span>
    <span class="formField">
        <label>Horas-aula cumpridas: <input type="number" name="professorworksheets:numClassTime" step="any" min="0" required="required" value="<?php echo $workSheetObject->classTime; ?>" /></label>
    </span>
    <span class="formField">
        <label><input type="checkbox" id="chkUseSubsAllowance" value="1" name="professorworksheets:chkUseSubsAllowance"
        <?php echo !is_null($workSheetObject->paymentSubsAllowanceTableId) ? ' checked="checked" ' : ''; ?>/>Pagar ajuda de custo</label>
    </span>
    <fieldset id="fsSubsAllowance" style="<?php echo !is_null($workSheetObject->paymentSubsAllowanceTableId) ? 'display:block;' : 'display: none;'; ?>">
        <legend>Ajuda de custo</legend>
        <span class="formField">
            <label>Nível: 
                <select id="selSubsAllowancePaymentLevel">
                    <?php foreach ($paymentInfosObj->paymentLevelTables as $ti => $pt): ?>
                        <optgroup label="<?php echo hscq($pt->tableName); ?>">
                            <?php foreach ($paymentInfosObj->paymentLevelTables[$ti]->levels as $li => $pl): ?>
                                <option value="<?php echo "$ti:$li"; ?>"
                                <?php echo writeSelectedStatus("{$workSheetObject->paymentSubsAllowanceTableId}:{$workSheetObject->paymentSubsAllowanceLevelId}", "$ti:$li"); ?>><?php echo hsc($pl->name . ' (Hora-aula: ' . formatDecimalToCurrency($pl->classTimeValue) . ')'); ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </label>
        </span>
        <span class="formField">
            <label>Horas-aula de ajuda de custo: <input type="number" id="numSubsAllowanceClassTime" name="professorworksheets:numSubsAllowanceClassTime" step="any" min="0" value="<?php echo $workSheetObject->paymentSubsAllowanceClassTime; ?>" /></label>
        </span>
    </fieldset>
    <span class="formField">
        <label>Recolher INSS? </label>
        <label><input type="radio" id="radCollectInssYes" name="professorworksheets:radCollectInss" required="required" value="1" <?php echo (bool)$workSheetObject->paymentInfosJson->collectInss ? 'checked="checked"' : '';?> /> Sim</label>
        <label><input type="radio" id="radCollectInssNo" name="professorworksheets:radCollectInss" required="required" value="0" <?php echo !(bool)$workSheetObject->paymentInfosJson->collectInss ? 'checked="checked"' : '';?> /> Não</label>
        <span id="lblProfessorCollectInssInfo"></span>
    </span>
    <span class="formField">
        <label>Desconto do INSS: <input type="number" required="required" step="any" min="0" max="100" name="professorworksheets:numInssPercent" value="<?php echo hscq($inssDiscountPercent); ?>" /> %</label>
    </span>

    <h3>Atividade exercida</h3>
    <span class="formField">
        <label>Nome: <input type="text" id="txtActivityName" name="professorworksheets:txtActivityName" size="40" value="<?php echo $workSheetObject->participationEventDataJson->activityName ?? ''; ?>"/></label>
    </span>
    <span class="formField">
        <label>Datas: <input type="text" name="professorworksheets:txtActivityDates" size="40" value="<?php echo $workSheetObject->participationEventDataJson->dates ?? ''; ?>"/></label>
    </span>
    <span class="formField">
        <label>Horários: <input type="text" name="professorworksheets:txtActivityTimes" size="30" value="<?php echo $workSheetObject->participationEventDataJson->times ?? ''; ?>" /></label>
    </span>
    <span class="formField">
        <label>Carga horária: <input type="text" name="professorworksheets:txtActivityWorkTime" size="40" value="<?php echo $workSheetObject->participationEventDataJson->workTime ?? ''; ?>" /></label>
    </span>

    <h3>Assinatura</h3>
    <span class="formField">
        <label>Permitir ao docente assinar a proposta da escola, declarações e recibo a partir de: <input type="date" name="professorworksheets:dateSignatureAllowed" required="required" value="<?php echo $workSheetObject->signatureDate; ?>" /></label>
    </span>

    <input type="hidden" id="hidPaymentTable" name="professorworksheets:hidPaymentTable" value="<?php echo $workSheetObject->paymentTableId; ?>" />
    <input type="hidden" id="hidPaymentLevel" name="professorworksheets:hidPaymentLevel" value="<?php echo $workSheetObject->paymentLevelId; ?>" />

    <input type="hidden" id="hidSubsAllowanceTable" name="professorworksheets:hidSubsAllowanceTable" value="<?php echo $workSheetObject->paymentSubsAllowanceTableId; ?>" />
    <input type="hidden" id="hidSubsAllowanceLevel" name="professorworksheets:hidSubsAllowanceLevel" value="<?php echo $workSheetObject->paymentSubsAllowanceLevelId; ?>" />

    <input type="hidden" name="professorworksheets:hidPaymentLevelTables" value="<?php echo hscq(json_encode($paymentInfosObj->paymentLevelTables)) ?>" />
    <input type="hidden" name="professorworksheets:hidProfessorTypes" value="<?php echo hscq(json_encode($paymentInfosObj->professorTypes)); ?>" />

    <input type="hidden" name="professorworksheets:profWorkSheetId" value="<?php echo $workSheetObject->id; ?>" />
    <br/>
    <div class="centControl">
        <input type="submit" name="btnsubmitSubmitWorkSheet" value="Editar ficha" />
    </div>
</form> 