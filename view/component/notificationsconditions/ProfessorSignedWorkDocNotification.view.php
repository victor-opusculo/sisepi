
<?php function writeOperatorsStatus($expectedOperator, $operatorId, $logicOperators)
{
    if (empty($logicOperators)) return '';
    return $expectedOperator === $logicOperators[$operatorId] ? ' checked ' : '';
} ?>

<script>
    const popupURL = '<?= URL\URLGenerator::generatePopupURL('{popup}') ?>';
    const getProfessorInfosScriptURL = '<?= URL\URLGenerator::generateFileURL('generate/getProfessorInfos.php') ?>';
    const getWorkProposalInfosScriptURL = '<?= URL\URLGenerator::generateFileURL('generate/getProfessorWorkProposalInfos.php') ?>';

    function btnLoadWorkProposal_onClick(e)
    {
        let wpId = Number(document.getElementById('numWorkProposalId').value);
        loadWorkProposalId(wpId);
    }

    function btnLoadProfessor_onClick(e)
    {
        let profId = Number(document.getElementById('numProfessorId').value);
        loadProfessorId(profId);
    }

    function btnDeleteIdRowButton_onClick(e)
    {
        const tr = this.parentNode.parentNode;
        const tbody = tr.parentNode;
        tbody.removeChild(tr);
    }

    function btnDeleteNameButton_onClick(e)
    {
        const li = this.parentNode;
        const ol = li.parentNode;
        ol.removeChild(li);
    }

    function btnSearchWorkProposal_onClick(e)
    {
        var popup = window.open(popupURL.replace('{popup}', 'selectprofessorworkproposal'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
        popup.focus();
    }

    function btnSearchProfessor_onClick(e)
    {
        var popup = window.open(popupURL.replace('{popup}', 'selectprofessor'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
        popup.focus();
    }

    function setProfessorWorkProposalIdInput(wpId)
    {
        loadWorkProposalId(wpId);
    }

    function setProfessorIdInput(professorId)
    {
        loadProfessorId(professorId);
    }

    function btnAddName_onClick(e)
    {
        const ol = document.getElementById('olProfessorNames');
        const newLi = document.createElement('li');

        newLi.innerHTML = `
        <input type="text" size="40" class="professorName" />
        <button type="button" class="deleteNameButton" style="min-width: 20px;">&times;</button>
        `;

        ol.appendChild(newLi);
        refreshDeleteButtonsEventListeners();
    }

    async function loadWorkProposalId(workProposalId)
    {
        try
        {
            let res = await fetch(getWorkProposalInfosScriptURL + '?id=' + workProposalId);
            let json = await res.json();

            if (json.error)
                throw new Error(json.error);

            if (json.data)
            {
                let tbody = document.getElementById('tableWorkProposalIds').querySelector('tbody');
                let newTr = document.createElement('tr'); 
                newTr.innerHTML = `
                <td>
                    ${json.data.id}
                    <input type="hidden" class="workProposalId" value="${json.data.id}"/>
                </td>
                <td>${json.data.name}</td>
                <td><button type="button" class="deleteIdRowButton" style="min-width: 20px;">&times;</button></td>`;

                tbody.appendChild(newTr);

                refreshDeleteButtonsEventListeners();
            }
        }
        catch (err)
        {
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err);
        }
    }

    async function loadProfessorId(professorId)
    {
        try
        {
            let res = await fetch(getProfessorInfosScriptURL + '?id=' + professorId);
            let json = await res.json();

            if (json.error)
                throw new Error(json.error);

            if (json.data)
            {
                let tbody = document.getElementById('tableProfessorIds').querySelector('tbody');
                let newTr = document.createElement('tr'); 
                newTr.innerHTML = `
                <td>
                    ${json.data.id}
                    <input type="hidden" class="professorId" value="${json.data.id}"/>
                </td>
                <td>${SisEpi.Layout.escapeHtml(json.data.name)}</td>
                <td><button type="button" class="deleteIdRowButton" style="min-width: 20px;">&times;</button></td>`;

                tbody.appendChild(newTr);

                refreshDeleteButtonsEventListeners();
            }
        }
        catch (err)
        {
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err);
        }
    }

    function refreshDeleteButtonsEventListeners()
    {
        document.querySelectorAll('.deleteIdRowButton').forEach( btn => btn.onclick = btnDeleteIdRowButton_onClick );
        document.querySelectorAll('.deleteNameButton').forEach( btn => btn.onclick = btnDeleteNameButton_onClick );
    }

    function generateConditionsJson()
    {
        const result = {
            workProposalId: [],
            professorId: [],
            professorName: [],
            operators: null
        };

        document.querySelectorAll('.workProposalId').forEach ( input => result.workProposalId.push(Number(input.value)) );

        document.querySelectorAll('.professorId').forEach ( input => result.professorId.push(Number(input.value)) );

        document.querySelectorAll('.professorName').forEach ( input => result.professorName.push(input.value) );


        let op1 = document.getElementsByName('logicOp1');
        let op1value = Array.from(op1).find( op => op.checked ).value;

        let op2 = document.getElementsByName('logicOp2');
        let op2value = Array.from(op2).find( op => op.checked ).value;

        result.operators = [ op1value, op2value ];

        return JSON.stringify(result);
    }

    function onFormSubmit(e)
    {
        document.getElementById('hidConditionsJson').value = generateConditionsJson();
    }

    window.addEventListener('load', function()
    {
        refreshDeleteButtonsEventListeners();
        document.getElementById('btnLoadWorkProposal').onclick = btnLoadWorkProposal_onClick;
        document.getElementById('btnSearchWorkProposal').onclick = btnSearchWorkProposal_onClick;

        document.getElementById('btnLoadProfessor').onclick = btnLoadProfessor_onClick;
        document.getElementById('btnSearchProfessor').onclick = btnSearchProfessor_onClick;

        document.getElementById('btnAddName').onclick = btnAddName_onClick;
    });
</script>

<div class="viewDataFrame">
    <label>Tipo de notificação: </label><?= $title ?>
</div>

<form id="frmConditions" method="post" onsubmit="onFormSubmit(event)" action="<?= URL\URLGenerator::generateFileURL('post/notifications.setconditions.post.php', [ 'cont' => $_GET['cont'], 'action' => 'subscribe' ]) ?>">
    
    <fieldset>
        <legend>Planos de aula específicos</legend>
        <span class="searchFormField">
            <label>Plano de aula ID: <input type="number" min="1" step="1" id="numWorkProposalId" /></label>
            <button type="button" id="btnLoadWorkProposal" style="min-width: 20px;"><?= hsc('>') ?></button>
            <button type="button" id="btnSearchWorkProposal" style="min-width: 20px;"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
        </span>
        <table id="tableWorkProposalIds">
            <thead>
                <tr><th>ID</th><th>Tema</th><th class="shrinkCell"></th></tr>
            </thead>
            <tbody>
                <?php if (isset($workProposals)): foreach ($workProposals as $wp): ?>
                    <tr data-id="<?= $wp->id ?>">
                        <td>
                            <?= $wp->id ?>
                            <input type="hidden" class="workProposalId" value="<?= $wp->id ?>"/>
                        </td>
                        <td><?= hsc($wp->name) ?></td>
                        <td><button type="button" class="deleteIdRowButton" style="min-width: 20px;">&times;</button></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </fieldset>

    <div class="centControl">
        <label><input type="radio" class="logicOp1" name="logicOp1" value="and" <?= writeOperatorsStatus('and', 0, $logicOperators) ?> required> E</label>
        <label><input type="radio" class="logicOp1" name="logicOp1" value="or" <?= writeOperatorsStatus('or', 0, $logicOperators) ?>> OU</label>
    </div>

    <fieldset>
        <legend>Docentes específicos</legend>
        <span class="searchFormField">
            <label>Docente ID: <input type="number" min="1" step="1" id="numProfessorId" /></label>
            <button type="button" id="btnLoadProfessor" style="min-width: 20px;"><?= hsc('>') ?></button>
            <button type="button" id="btnSearchProfessor" style="min-width: 20px;"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
        </span>
        <table id="tableProfessorIds">
            <thead>
                <tr><th>ID</th><th>Nome</th><th class="shrinkCell"></th></tr>
            </thead>
            <tbody>
                <?php if (isset($professors)): foreach ($professors as $prof): ?>
                    <tr data-id="<?= $prof->id ?>">
                        <td>
                            <?= $prof->id ?>
                            <input type="hidden" class="professorId" value="<?= $prof->id ?>"/>
                        </td>
                        <td><?= hsc($prof->name) ?></td>
                        <td><button type="button" class="deleteIdRowButton" style="min-width: 20px;">&times;</button></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </fieldset>

    <div class="centControl">
        <label><input type="radio" class="logicOp2" name="logicOp2" value="and" <?= writeOperatorsStatus('and', 1, $logicOperators) ?> required> E</label>
        <label><input type="radio" class="logicOp2" name="logicOp2" value="or" <?= writeOperatorsStatus('or', 1, $logicOperators) ?>> OU</label>
    </div>

    <fieldset>
        <legend>Nomes de docente específicos</legend>
        <ol id="olProfessorNames">
            <?php if (isset($professorNames)): foreach ($professorNames as $name): ?>
                <li>
                    <input type="text" size="40" class="professorName" value="<?= hscq($name) ?>"/>
                    <button type="button" class="deleteNameButton" style="min-width: 20px;">&times;</button>
                </li>
            <?php endforeach; endif; ?>
        </ol>
        <button type="button" id="btnAddName">Adicionar</button>
    </fieldset>

    <div class="centControl">
        <input type="hidden" name="hidModule" value="<?= $notModule ?? '' ?>" />
        <input type="hidden" name="hidId" value="<?= $notId ?? '' ?>" />
        <input type="hidden" id="hidConditionsJson" name="hidConditionsJson" value="<?= $conditionsJson ?? '' ?>" />
        <input type="submit" name="btnsubmitSubmitConditions" value="Salvar condições" />
        <input type="submit" name="btnsubmitDeleteConditions" value="Excluir condições" />
    </div>

</form>