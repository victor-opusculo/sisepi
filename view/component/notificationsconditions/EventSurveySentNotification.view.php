
<?php function writeOperatorsStatus($expectedOperator, $operatorId, $logicOperators)
{
    if (empty($logicOperators)) return '';
    return $expectedOperator === $logicOperators[$operatorId] ? ' checked ' : '';
} ?>

<script>
    const popupURL = '<?= URL\URLGenerator::generatePopupURL('{popup}') ?>';
    const getEventInfosScriptURL = '<?= URL\URLGenerator::generateFileURL('generate/getEventInfos.php') ?>';

    function btnLoadEvent_onClick(e)
    {
        let eventId = Number(document.getElementById('numEventId').value);
        loadEventId(eventId);
    }

    function btnDeleteIdRowButton_onClick(e)
    {
        const tr = this.parentNode.parentNode;
        const tbody = tr.parentNode;
        tbody.removeChild(tr);
    }

    function btnSearchEvent_onClick(e)
    {
        var popup = window.open(popupURL.replace('{popup}', 'selectevent'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
        popup.focus();
    }

    function deleteFieldButton_onClick(e)
    {
        let ol = this.parentNode.parentNode;
        let li = this.parentNode;

        ol.removeChild(li);
    }

    function btnAddSurveyField_onClick(e)
    {
        let blueprint = document.querySelector('.surveyFieldExpectedTitle');
        let cloned = blueprint.cloneNode(true);
        let deleteButton = document.querySelector('#pageElementTemplates .deleteFieldButton').cloneNode(true);

        if (blueprint.children.length >= 1)
        {
            cloned.onchange = surveyFieldExpectedTitle_onChange;
            deleteButton.onclick = deleteFieldButton_onClick;

            let ol = document.getElementById('olSurveyFields');
            let newLi = document.createElement('li');

            newLi.appendChild(cloned);
            newLi.append(' ');
            newLi.appendChild(deleteButton);

            ol.appendChild(newLi);

            surveyFieldExpectedTitle_onChange.apply(cloned);
        }
    }

    function surveyFieldExpectedTitle_onChange(e)
    {
        let li = this.parentNode;
        let oldValues;

        let deleteButton = li.querySelector('.deleteFieldButton');
        let valuesSelectBlueprint = document.querySelector(`#pageElementTemplates select[data-field-title='${this.value}']`);

        if (valuesSelectBlueprint)
        {
            let valuesSelectCloned = valuesSelectBlueprint.cloneNode(true);
            if (oldValues = li.querySelector('.surveyFieldExpectedValue')) li.removeChild(oldValues);
            li.insertBefore(valuesSelectCloned, deleteButton);
        }
        else
        {
            let valuesInputText = document.querySelector("#pageElementTemplates input[type='text'].surveyFieldExpectedValue");
            if (oldValues = li.querySelector('.surveyFieldExpectedValue')) li.removeChild(oldValues);
            li.insertBefore(valuesInputText, deleteButton);
        }
    }

    function setEventIdInput(eventId)
    {
        loadEventId(eventId);
    }

    async function loadEventId(eventId)
    {
        try
        {
            let res = await fetch(getEventInfosScriptURL + '?id=' + eventId);
            let json = await res.json();

            if (json.error)
                throw new Error(json.error);

            if (json.data)
            {
                let tbody = document.getElementById('tableEventIds').querySelector('tbody');
                let newTr = document.createElement('tr'); 
                newTr.innerHTML = `
                <td>
                    ${json.data.id}
                    <input type="hidden" class="eventId" value="${json.data.id}"/>
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

    function refreshDeleteButtonsEventListeners()
    {
        document.querySelectorAll('.deleteIdRowButton').forEach( btn => btn.onclick = btnDeleteIdRowButton_onClick );
        document.querySelectorAll('.deleteFieldButton').forEach( btn => btn.onclick = deleteFieldButton_onClick );
    }

    function generateConditionsJson()
    {
        const result = {
            eventId: [],
            surveyField: {},
            operators: null
        };

        document.querySelectorAll('.eventId').forEach ( input => result.eventId.push(Number(input.value)) );

        document.querySelectorAll('#olSurveyFields li').forEach ( li => 
        {
            let title = li.querySelector('.surveyFieldExpectedTitle').value;
            let value = li.querySelector('.surveyFieldExpectedValue').value;

            if (!result.surveyField[title])
                result.surveyField[title] = [];

            result.surveyField[title].push(value);
        });

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
        document.getElementById('btnLoadEvent').onclick = btnLoadEvent_onClick;
        document.getElementById('btnSearchEvent').onclick = btnSearchEvent_onClick;
        document.getElementById('btnAddSurveyField').onclick = btnAddSurveyField_onClick;
        document.querySelectorAll('#frmConditions .surveyFieldExpectedTitle').forEach( sel => sel.onchange = surveyFieldExpectedTitle_onChange );
    });
</script>

<div id="pageElementTemplates" style="display: none;">
    <select class="surveyFieldExpectedTitle">
        <?php foreach ($templateFieldsTitlesAndValues as $_title => $values): ?>
            <option><?= hsc($_title) ?></option>
        <?php endforeach; ?>
    </select>

    <?php foreach ($templateFieldsTitlesAndValues as $_title => $values): ?>
    <?php if (empty($values)) continue; ?>
        <select class="surveyFieldExpectedValue" data-field-title="<?= hscq($_title) ?>">
            <?php foreach ($values as $val => $label): ?>
                <option value="<?= hscq($val) ?>"><?= hsc($label) ?></option>
            <?php endforeach; ?>
        </select>
    <?php endforeach; ?>

    <input type="text" size="30" class="surveyFieldExpectedValue" list="listSurveyField_FreeText" />

    <button type="button" class="deleteFieldButton" style="min-width: 20px;">&times;</button>
</div>


<div class="viewDataFrame">
    <label>Tipo de notificação: </label><?= $title ?>
</div>

<form id="frmConditions" method="post" onsubmit="onFormSubmit(event)" action="<?= URL\URLGenerator::generateFileURL('post/notifications.setconditions.post.php', [ 'cont' => $_GET['cont'], 'action' => 'subscribe' ]) ?>">
    <style>
        #frmConditions select { width: 40%; }
    </style>
    <fieldset>
        <legend>Eventos específicos</legend>
        <span class="searchFormField">
            <label>Evento ID: <input type="number" min="1" step="1" id="numEventId" /></label>
            <button type="button" id="btnLoadEvent" style="min-width: 20px;"><?= hsc('>') ?></button>
            <button type="button" id="btnSearchEvent" style="min-width: 20px;"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
        </span>
        <table id="tableEventIds">
            <thead>
                <tr><th>ID</th><th>Nome</th><th class="shrinkCell"></th></tr>
            </thead>
            <tbody>
                <?php if (isset($events)): foreach ($events as $ev): ?>
                    <tr data-id="<?= $ev->id ?>">
                        <td>
                            <?= $ev->id ?>
                            <input type="hidden" class="eventId" value="<?= $ev->id ?>"/>
                        </td>
                        <td><?= hsc($ev->name) ?></td>
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
        <legend>Respostas específicas aos campos da pesquisa de satisfação</legend>

        <datalist id="listSurveyField_FreeText">
            <option value="{não em branco}"></option>
        </datalist>

        <ol id="olSurveyFields">
            <?php if (isset($surveyFields)): foreach ($surveyFields as $field): ?>
                <li>
                    <select class="surveyFieldExpectedTitle">
                        <?php foreach ($templateFieldsTitlesAndValues as $title => $values): ?>
                            <option <?= $field['title'] === $title ? ' selected ' : '' ?>><?= hsc($title) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <?php if (empty($templateFieldsTitlesAndValues[$field['title']])): ?>
                        <input type="text" size="30" class="surveyFieldExpectedValue" list="listSurveyField_FreeText" value="<?= hscq($field['value']) ?>" />
                    <?php else: ?>
                        <select class="surveyFieldExpectedValue">
                            <?php foreach ($templateFieldsTitlesAndValues[$field['title']] as $val => $label): ?>
                                <option value="<?= hscq($val) ?>" <?= $val == $field['value'] ? ' selected ':'' ?> ><?= hsc($label) ?></option>
                            <?php endforeach; ?>
                        </select><?php endif; ?><button type="button" class="deleteFieldButton" style="min-width: 20px;">&times;</button>
                </li>
            <?php endforeach; endif; ?>
        </ol>
        <button type="button" id="btnAddSurveyField">Adicionar</button>
    </fieldset>

    <div class="centControl">
        <input type="hidden" name="hidModule" value="<?= $notModule ?? '' ?>" />
        <input type="hidden" name="hidId" value="<?= $notId ?? '' ?>" />
        <input type="hidden" id="hidConditionsJson" name="hidConditionsJson" value="<?= hscq($conditionsJson) ?? '' ?>" />
        <input type="submit" name="btnsubmitSubmitConditions" value="Salvar condições" />
        <input type="submit" name="btnsubmitDeleteConditions" value="Excluir condições" />
    </div>

</form>