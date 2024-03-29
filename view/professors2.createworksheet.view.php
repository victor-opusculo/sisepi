<script>
    const getEventInfosScriptURL = '<?php echo URL\URLGenerator::generateFileURL('generate/getEventInfos.php'); ?>';
    const getProfessorInfosScriptURL = '<?php echo URL\URLGenerator::generateFileURL('generate/getProfessorInfos.php'); ?>';
    const popupURL = '<?php echo URL\URLGenerator::generatePopupURL("{popup}"); ?>';
    const availableDiscounts = JSON.parse('<?= json_encode($paymentInfosObj->discounts ?? '') ?>');
    const usedDiscounts = {};
</script>
<script src="<?php echo URL\URLGenerator::generateFileURL('view/professors2.editworksheet.view.js'); ?>"></script>

<form id="frmEditWorkSheet" method="post" action="<?php echo URL\URLGenerator::generateFileURL('post/professors2.createworksheet.post.php', [ 'title' => $this->subtitle ]); ?>">
    <h3>Proposta de trabalho vinculada</h3>
    <div class="viewDataFrame">
        <label>Nome: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('professors2', 'viewworkproposal', $proposalObject->id); ?>">
        <?php echo hsc($proposalObject->name ?? ''); ?></a> <br/>
        <label>Descrição: </label><?php echo nl2br(hsc($proposalObject->description ?? '')); ?>
        <input type="hidden" name="professorworksheets:professorWorkProposalId" value="<?php echo $proposalObject->id; ?>" />
    </div>

    <h3>Evento vinculado</h3>
    <span style="display: flex; align-items: center;">
        <label>Evento ID: 
            <input type="number" id="numEventId" name="professorworksheets:numEventId" min="1" step="1" />
        </label>
        <button type="button" id="btnLoadEvent" style="min-width:20px;" ><?php echo htmlspecialchars(">"); ?></button>
		<button type="button" id="btnSearchEvent"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
    </span>
    <div class="viewDataFrame">
        <label>Nome: </label><span id="lblEventName"></span> <br/>
        <label>Tipo: </label><span id="lblEventType"></span> <br/>
        <label>Modalidade: </label><span id="lblEventMode"></span>
    </div>

    <h3>Docente desta ficha</h3>
    <span style="display: flex; align-items: center;">
        <label>Docente ID: 
            <input type="number" id="numProfessorId" name="professorworksheets:numProfessorId" min="1" step="1" required="required" />
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
        <label>Professor recolhe INSS? </label>
        <label><input type="radio" id="radCollectInssYes" name="professorworksheets:radCollectInss" required="required" value="1"/> Sim</label>
        <label><input type="radio" id="radCollectInssNo" name="professorworksheets:radCollectInss" required="required" value="0"/> Não</label>
        <span id="lblProfessorCollectInssInfo"></span>
    </span>
    <fieldset id="fsInssDeclaration" style="display:none;">
        <legend>Declaração INSS</legend>
        <span class="formField">
            <label>Período: <input type="date" id="dateInssPeriodBegin" class="dateInssPeriod" name="professorworksheets:dateInssPeriodBegin" /></label>
            <label>a <input type="date" id="dateInssPeriodEnd" class="dateInssPeriod" name="professorworksheets:dateInssPeriodEnd"/></label>
        </span>
        <table>
            <thead>
                <tr>
                    <th>Empresa</th><th>CNPJ</th><th>Remuneração</th><th>INSS retido</th>
                </tr>
            </thead>
            <tbody style="text-align: center;">
                <?php
                for ($i = 0; $i < 4; $i++): 
                    ?>
                    <tr>
                        <td><input type="text" name="professorworksheets:inssCompanies[<?php echo $i; ?>][name]" maxlength="120" size="35"/></td>
                        <td><input type="text" name="professorworksheets:inssCompanies[<?php echo $i; ?>][cnpj]" maxlength="120" size="15"/></td>
                        <td><input type="number" name="professorworksheets:inssCompanies[<?php echo $i; ?>][wage]" min="0" step="any" maxlength="120" style="width:150px;"/></td>
                        <td><input type="number" name="professorworksheets:inssCompanies[<?php echo $i; ?>][collectedInss]" step="any" maxlength="120" style="width:150px;"/></td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </fieldset>
    <span class="formField">
        <label>Mês de referência: 
            <select id="selReferenceMonth">
                <?php foreach ($monthList as $k => $v) { echo '<option value="' . ($k + 1) . '" ' . (($k + 1) == date('m') ? ' selected ' : '') .'>' . $v . '</option>'; } ?>  
            </select>
        </label>
        <input id="numReferenceYear" type="number" min="2000" step="1" value="<?php echo date('Y'); ?>"/>
        <input type="hidden" id="hidReferenceMonth" name="professorworksheets:hidReferenceMonth" value="<?php echo date('Y-m-01'); ?>" />
    </span>

    <h3>Atividade exercida</h3>
    <span class="formField">
        <label>Nome: <input type="text" id="txtActivityName" name="professorworksheets:txtActivityName" size="40" /></label>
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

    <h3>Certificado de docente</h3>
    <span class="formField">
        <label><input id="chkUseProfessorCertificate" type="checkbox" value="1" name="professorworksheets:chkUseCertificate"/> Fornecer certificado de docente</label>
    </span>
    <div id="divCertificateText" style="display:none;">
        <label>Texto para o certificado: 
            <textarea name="professorworksheets:txtCertificateText" rows="4" maxlength="600"></textarea>
        </label>
        <label>Imagem de fundo do certificado: <input type="text" name="professorworksheets:txtCertificateBgFile" required="required" size="40" maxlength="255" value="<?php echo $defaultCertBgFile; ?>"/></label>
    </div>

    <h3>Documentação para empenho</h3>
    <span class="formField">
        <label>Modelo de documentação: 
            <select name="professorworksheets:selDocTemplate">
                <?php foreach ($profDocTemplates as $dt): ?>
                    <option value="<?php echo $dt['id']; ?>"><?php echo $dt['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </span>
    <span class="formField">
        <label>Permitir ao docente assinar a documentação a partir de: <input type="date" name="professorworksheets:dateSignatureAllowed" required="required"/></label>
    </span>

    <input type="hidden" id="hidPaymentTable" name="professorworksheets:hidPaymentTable" value="0" />
    <input type="hidden" id="hidPaymentLevel" name="professorworksheets:hidPaymentLevel" value="0" />

    <input type="hidden" id="hidSubsAllowanceTable" name="professorworksheets:hidSubsAllowanceTable" value="" />
    <input type="hidden" id="hidSubsAllowanceLevel" name="professorworksheets:hidSubsAllowanceLevel" value="" />

    <input type="hidden" name="professorworksheets:hidPaymentLevelTables" value="<?php echo hscq(json_encode($paymentInfosObj->paymentLevelTables)) ?>" />
    <input type="hidden" name="professorworksheets:hidProfessorTypes" value="<?php echo hscq(json_encode($paymentInfosObj->professorTypes)); ?>" />
    <input type="hidden" id="hidDiscounts" name="professorworksheets:hidDiscounts" value="" />

    <br/>
    <div class="centControl">
        <input type="submit" name="btnsubmitSubmitWorkSheet" value="Criar ficha" />
    </div>
</form> 