
<?php function writeOperatorsStatus($expectedOperator, $operatorId, $logicOperators)
{
    if (empty($logicOperators)) return '';
    return $expectedOperator === $logicOperators[$operatorId] ? ' checked ' : '';
} ?>

<script>
    const popupURL = '<?= URL\URLGenerator::generatePopupURL('{popup}') ?>';
    const getLegislatureInfosScriptURL = '<?= URL\URLGenerator::generateFileURL('generate/getVmLegislatureInfos.php') ?>';
    const getVmParentInfosScriptURL = '<?= URL\URLGenerator::generateFileURL('generate/getVmParentInfos.php') ?>';

    function btnLoadLegislature_onClick(e)
    {
        let legId = Number(document.getElementById('numLegislatureId').value);
        loadLegislatureId(legId);
    }

    function btnLoadVmParent_onClick(e)
    {
        let parentId = Number(document.getElementById('numVmParentId').value);
        loadParentId(parentId);
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

    function btnSearchLegislature_onClick(e)
    {
        var popup = window.open(popupURL.replace('{popup}', 'selectvmlegislature'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
        popup.focus();
    }

    function btnSearchVmParent_onClick(e)
    {
        var popup = window.open(popupURL.replace('{popup}', 'selectvmparent'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
        popup.focus();
    }

    function setVmLegislatureIdInput(legId)
    {
        loadLegislatureId(legId);
    }

    function setVmParentIdInput(parentId)
    {
        loadParentId(parentId);
    }

    async function loadLegislatureId(legislatureId)
    {
        try
        {
            let res = await fetch(getLegislatureInfosScriptURL + '?id=' + legislatureId);
            let json = await res.json();

            if (json.error)
                throw new Error(json.error);

            if (json.data)
            {
                let tbody = document.getElementById('tableLegislatureIds').querySelector('tbody');
                let newTr = document.createElement('tr'); 
                newTr.innerHTML = `
                <td>
                    ${json.data.id}
                    <input type="hidden" class="legislatureId" value="${json.data.id}"/>
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

    async function loadParentId(parentId)
    {
        try
        {
            let res = await fetch(getVmParentInfosScriptURL + '?id=' + parentId);
            let json = await res.json();

            if (json.error)
                throw new Error(json.error);

            if (json.data)
            {
                let tbody = document.getElementById('tableVmParentIds').querySelector('tbody');
                let newTr = document.createElement('tr'); 
                newTr.innerHTML = `
                <td>
                    ${json.data.id}
                    <input type="hidden" class="vmParentId" value="${json.data.id}"/>
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
    }

    function generateConditionsJson()
    {
        const result = {
            vmLegislatureId: [],
            vmParentId: [],
            operators: null
        };

        document.querySelectorAll('.legislatureId').forEach ( input => result.vmLegislatureId.push(Number(input.value)) );

        document.querySelectorAll('.vmParentId').forEach ( input => result.vmParentId.push(Number(input.value)) );


        let op1 = document.getElementsByName('logicOp1');
        let op1value = Array.from(op1).find( op => op.checked ).value;

        result.operators = [ op1value ];

        return JSON.stringify(result);
    }

    function onFormSubmit(e)
    {
        document.getElementById('hidConditionsJson').value = generateConditionsJson();
    }

    window.addEventListener('load', function()
    {
        refreshDeleteButtonsEventListeners();
        document.getElementById('btnLoadLegislature').onclick = btnLoadLegislature_onClick;
        document.getElementById('btnSearchLegislature').onclick = btnSearchLegislature_onClick;

        document.getElementById('btnLoadVmParent').onclick = btnLoadVmParent_onClick;
        document.getElementById('btnSearchVmParent').onclick = btnSearchVmParent_onClick;
    });
</script>

<div class="viewDataFrame">
    <label>Tipo de notificação: </label><?= $title ?>
</div>

<form id="frmConditions" method="post" onsubmit="onFormSubmit(event)" action="<?= URL\URLGenerator::generateFileURL('post/notifications.setconditions.post.php', [ 'cont' => $_GET['cont'], 'action' => 'subscribe' ]) ?>">
    
    <fieldset>
        <legend>Legislaturas específicas</legend>
        <span class="searchFormField">
            <label>Legislatura ID: <input type="number" min="1" step="1" id="numLegislatureId" /></label>
            <button type="button" id="btnLoadLegislature" style="min-width: 20px;"><?= hsc('>') ?></button>
            <button type="button" id="btnSearchLegislature" style="min-width: 20px;"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
        </span>
        <table id="tableLegislatureIds">
            <thead>
                <tr><th>ID</th><th>Nome</th><th class="shrinkCell"></th></tr>
            </thead>
            <tbody>
                <?php if (isset($legislatures)): foreach ($legislatures as $leg): ?>
                    <tr data-id="<?= $leg->id ?>">
                        <td>
                            <?= $leg->id ?>
                            <input type="hidden" class="legislatureId" value="<?= $leg->id ?>"/>
                        </td>
                        <td><?= hsc($leg->name) ?></td>
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
        <legend>Pais/Responsáveis específicos</legend>
        <span class="searchFormField">
            <label>Responsável ID: <input type="number" min="1" step="1" id="numVmParentId" /></label>
            <button type="button" id="btnLoadVmParent" style="min-width: 20px;"><?= hsc('>') ?></button>
            <button type="button" id="btnSearchVmParent" style="min-width: 20px;"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
        </span>
        <table id="tableVmParentIds">
            <thead>
                <tr><th>ID</th><th>Nome</th><th class="shrinkCell"></th></tr>
            </thead>
            <tbody>
                <?php if (isset($vmParents)): foreach ($vmParents as $parent): ?>
                    <tr data-id="<?= $parent->id ?>">
                        <td>
                            <?= $parent->id ?>
                            <input type="hidden" class="vmParentId" value="<?= $parent->id ?>"/>
                        </td>
                        <td><?= hsc($parent->name) ?></td>
                        <td><button type="button" class="deleteIdRowButton" style="min-width: 20px;">&times;</button></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </fieldset>

    <div class="centControl">
        <input type="hidden" name="hidModule" value="<?= $notModule ?? '' ?>" />
        <input type="hidden" name="hidId" value="<?= $notId ?? '' ?>" />
        <input type="hidden" id="hidConditionsJson" name="hidConditionsJson" value="<?= $conditionsJson ?? '' ?>" />
        <input type="submit" name="btnsubmitSubmitConditions" value="Salvar condições" />
        <input type="submit" name="btnsubmitDeleteConditions" value="Excluir condições" />
    </div>

</form>