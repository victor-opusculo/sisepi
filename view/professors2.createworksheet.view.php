<script src="<?php echo URL\URLGenerator::generateFileURL('view/professors2.editworksheet.view.js'); ?>"></script>

<form method="post" action="<?php echo URL\URLGenerator::generateFileURL('post/professors2.createworksheet.post.php', [ 'title' => $this->subtitle ]); ?>">
    <h3>Proposta de trabalho vinculada</h3>
    <div class="viewDataFrame">
        <label>Nome: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('professors2', 'viewworkproposal', $proposalObject->id); ?>">
        <?php echo hsc($proposalObject->name); ?></a> <br/>
        <label>Descrição: </label><?php echo nl2br(hsc($proposalObject->description)); ?>
        <input type="hidden" name="professorworksheets:professorWorkProposalId" value="<?php echo $proposalObject->id; ?>" />
    </div>

    <h3>Evento vinculado</h3>
    <span class="formField">
        <label>Evento: 
            <select id="selEventId" name="professorworksheets:selEventId">
                <?php foreach ($eventsList as $ev): ?>
                    <option value="<?php echo $ev['id']; ?>"><?php echo hsc($ev['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </span>

    <h3>Docente desta ficha</h3>
    <span class="formField">
        <label>Docente: 
            <select name="professorworksheets:selProfessorId">
                <?php foreach ($professorsList as $p): ?>
                    <option value="<?php echo $p['id']; ?>"><?php echo hsc($p['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </span>
    <span class="formField">
        <label>Função: 
            <select name="professorworksheets:selProfessorType">
                <?php foreach ($paymentInfosObj->professorTypes as $i => $pt): ?>
                    <option value="<?php echo $i; ?>"><?php echo hsc($pt->name); ?></option>
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
                            <option value="<?php echo "$ti:$li"; ?>"><?php echo hsc($pl->name . ' (Hora-aula: ' . formatDecimalToCurrency($pl->classTimeValue) . ')'); ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
        </label>
    </span>
    <span class="formField">
        <label>Horas-aula cumpridas: <input type="number" name="professorworksheets:numClassTime" step="any" min="0" required="required" /></label>
    </span>
    <span class="formField">
        <label><input type="checkbox" id="chkUseSubsAllowance" value="1" name="professorworksheets:chkUseSubsAllowance"/>Pagar ajuda de custo</label>
    </span>
    <fieldset id="fsSubsAllowance" style="display: none;">
        <legend>Ajuda de custo</legend>
        <span class="formField">
            <label>Nível: 
                <select id="selSubsAllowancePaymentLevel">
                    <?php foreach ($paymentInfosObj->paymentLevelTables as $ti => $pt): ?>
                        <optgroup label="<?php echo hscq($pt->tableName); ?>">
                            <?php foreach ($paymentInfosObj->paymentLevelTables[$ti]->levels as $li => $pl): ?>
                                <option value="<?php echo "$ti:$li"; ?>"><?php echo hsc($pl->name . ' (Hora-aula: ' . formatDecimalToCurrency($pl->classTimeValue) . ')'); ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </label>
        </span>
        <span class="formField">
            <label>Horas-aula de ajuda de custo: <input type="number" id="numSubsAllowanceClassTime" name="professorworksheets:numSubsAllowanceClassTime" step="any" min="0" /></label>
        </span>
    </fieldset>
    <span class="formField">
        <label>Recolher INSS? </label>
        <label><input type="radio" name="professorworksheets:radCollectInss" required="required" value="1"/> Sim</label>
        <label><input type="radio" name="professorworksheets:radCollectInss" required="required" value="0"/> Não</label>
    </span>
    <span class="formField">
        <label>Desconto do INSS: <input type="number" required="required" step="any" min="0" max="100" name="professorworksheets:numInssPercent" value="<?php echo hscq($inssDiscountPercent); ?>" /> %</label>
    </span>

    <h3>Atividade exercida</h3>
    <span class="formField">
        <label>Nome: <input type="text" name="professorworksheets:txtActivityName" size="40" /></label>
    </span>
    <span class="formField">
        <label>Datas: <input type="text" name="professorworksheets:txtActivityDates" size="40" /></label>
    </span>
    <span class="formField">
        <label>Horários: <input type="text" name="professorworksheets:txtActivityTimes" size="30" /></label>
    </span>
    <span class="formField">
        <label>Carga horária: <input type="text" name="professorworksheets:txtActivityWorkTime" size="40" /></label>
    </span>

    <input type="hidden" id="hidPaymentTable" name="professorworksheets:hidPaymentTable" value="" />
    <input type="hidden" id="hidPaymentLevel" name="professorworksheets:hidPaymentLevel" value="" />

    <input type="hidden" id="hidSubsAllowanceTable" name="professorworksheets:hidSubsAllowanceTable" value="" />
    <input type="hidden" id="hidSubsAllowanceLevel" name="professorworksheets:hidSubsAllowanceLevel" value="" />

    <input type="hidden" name="professorworksheets:hidPaymentLevelTables" value="<?php echo hscq(json_encode($paymentInfosObj->paymentLevelTables)) ?>" />
    <input type="hidden" name="professorworksheets:hidProfessorTypes" value="<?php echo hscq(json_encode($paymentInfosObj->professorTypes)); ?>" />
</form> 