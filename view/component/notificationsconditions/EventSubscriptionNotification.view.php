
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

    function btnDeleteEmailButton_onClick(e)
    {
        const li = this.parentNode;
        const ol = li.parentNode;
        ol.removeChild(li);
    }

    function btnSearchEvent_onClick(e)
    {
        var popup = window.open(popupURL.replace('{popup}', 'selectevent'), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=900,height=500");
        popup.focus();
    }

    function setEventIdInput(eventId)
    {
        loadEventId(eventId);
    }

    function btnAddEmail_onClick(e)
    {
        const ol = document.getElementById('olStudentEmails');
        const newLi = document.createElement('li');

        newLi.innerHTML = `
        <input type="email" size="40" class="studentEmail" />
        <button type="button" class="deleteEmailButton" style="min-width: 20px;">&times;</button>
        `;

        ol.appendChild(newLi);
        refreshDeleteButtonsEventListeners();
    }

    function btnAddName_onClick(e)
    {
        const ol = document.getElementById('olStudentNames');
        const newLi = document.createElement('li');

        newLi.innerHTML = `
        <input type="text" size="40" class="studentName" />
        <button type="button" class="deleteEmailButton" style="min-width: 20px;">&times;</button>
        `;

        ol.appendChild(newLi);
        refreshDeleteButtonsEventListeners();
    }

    function btnAddSubscriptionField_onClick(e)
    {
        const ol = document.getElementById('olSubscriptionFields');
        const newLi = document.createElement('li');

        newLi.innerHTML = `
        <input type="text" placeholder="Identificador" list="listSubscriptionFieldsIdentifiers" size="20" class="studentSubsFieldIdentifier" value=""/>
        <input type="text" placeholder="Resposta esperada" size="40" class="studentSubsFieldValue" value="" />
        <button type="button" class="deleteEmailButton" style="min-width: 20px;">&times;</button>
        `;

        ol.appendChild(newLi);
        refreshDeleteButtonsEventListeners();
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
        document.querySelectorAll('.deleteEmailButton').forEach( btn => btn.onclick = btnDeleteEmailButton_onClick );
    }

    function generateConditionsJson()
    {
        const result = {
            eventId: [],
            studentEmail: [],
            studentName: [],
            studentSubscriptionField: {},
            operators: null
        };

        document.querySelectorAll('.eventId').forEach ( input => result.eventId.push(Number(input.value)) );

        document.querySelectorAll('.studentEmail').forEach ( input => result.studentEmail.push(input.value.toLowerCase()) );

        document.querySelectorAll('.studentName').forEach ( input => result.studentName.push(input.value) );

        document.querySelectorAll('#olSubscriptionFields li').forEach( li =>
        {
            let ident = li.querySelector('.studentSubsFieldIdentifier').value;
            let value = li.querySelector('.studentSubsFieldValue').value;

            if (typeof(result.studentSubscriptionField[ident]) === 'undefined')
                result.studentSubscriptionField[ident] = [];

            result.studentSubscriptionField[ident].push(value);
        });

        let op1 = document.getElementsByName('logicOp1');
        let op1value = Array.from(op1).find( op => op.checked ).value;

        let op2 = document.getElementsByName('logicOp2');
        let op2value = Array.from(op2).find( op => op.checked ).value;

        let op3 = document.getElementsByName('logicOp3');
        let op3value = Array.from(op3).find( op => op.checked ).value;

        result.operators = [ op1value, op2value, op3value ];

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
        document.getElementById('btnAddEmail').onclick = btnAddEmail_onClick;
        document.getElementById('btnAddName').onclick = btnAddName_onClick;
        document.getElementById('btnAddSubscriptionField').onclick = btnAddSubscriptionField_onClick;
    });
</script>

<div class="viewDataFrame">
    <label>Tipo de notificação: </label><?= $title ?>
</div>

<form id="frmConditions" method="post" onsubmit="onFormSubmit(event)" action="<?= URL\URLGenerator::generateFileURL('post/notifications.setconditions.post.php', [ 'cont' => $_GET['cont'], 'action' => 'subscribe' ]) ?>">
    
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
        <legend>E-mails de inscrito específicos</legend>
        <ol id="olStudentEmails">
            <?php if (isset($studentEmails)): foreach ($studentEmails as $email): ?>
                <li>
                    <input type="email" size="40" class="studentEmail" value="<?= hscq($email) ?>"/>
                    <button type="button" class="deleteEmailButton" style="min-width: 20px;">&times;</button>
                </li>
            <?php endforeach; endif; ?>
        </ol>
        <button type="button" id="btnAddEmail">Adicionar</button>
    </fieldset>

    <div class="centControl">
        <label><input type="radio" class="logicOp2" name="logicOp2" value="and" <?= writeOperatorsStatus('and', 1, $logicOperators) ?> required> E</label>
        <label><input type="radio" class="logicOp2" name="logicOp2" value="or" <?= writeOperatorsStatus('or', 1, $logicOperators) ?>> OU</label>
    </div>

    <fieldset>
        <legend>Nomes de inscrito específicos</legend>
        <ol id="olStudentNames">
            <?php if (isset($studentNames)): foreach ($studentNames as $name): ?>
                <li>
                    <input type="text" size="40" class="studentName" value="<?= hscq($name) ?>"/>
                    <button type="button" class="deleteEmailButton" style="min-width: 20px;">&times;</button>
                </li>
            <?php endforeach; endif; ?>
        </ol>
        <button type="button" id="btnAddName">Adicionar</button>
    </fieldset>

    <div class="centControl">
        <label><input type="radio" class="logicOp2" name="logicOp3" value="and" <?= writeOperatorsStatus('and', 2, $logicOperators) ?> required> E</label>
        <label><input type="radio" class="logicOp2" name="logicOp3" value="or" <?= writeOperatorsStatus('or', 2, $logicOperators) ?>> OU</label>
    </div>

    <fieldset>
        <legend>Respostas específicas aos campos da inscrição</legend>
        <datalist id="listSubscriptionFieldsIdentifiers">
            <?php foreach ($subscriptionIdentifiers as $ident): ?>
                <option><?= $ident ?></option>
            <?php endforeach; ?>
        </datalist>
        <ol id="olSubscriptionFields">
            <?php if (isset($studentSubscriptionFields)): foreach ($studentSubscriptionFields as $field): ?>
                <li>
                    <input type="text" list="listSubscriptionFieldsIdentifiers" size="20" class="studentSubsFieldIdentifier" value="<?= hscq($field['identifier']) ?>"/>
                    <input type="text" size="40" class="studentSubsFieldValue" value="<?= hscq($field['value']) ?>" />
                    <button type="button" class="deleteEmailButton" style="min-width: 20px;">&times;</button>
                </li>
            <?php endforeach; endif; ?>
        </ol>
        <button type="button" id="btnAddSubscriptionField">Adicionar</button>
    </fieldset>

    <div class="centControl">
        <input type="hidden" name="hidModule" value="<?= $notModule ?? '' ?>" />
        <input type="hidden" name="hidId" value="<?= $notId ?? '' ?>" />
        <input type="hidden" id="hidConditionsJson" name="hidConditionsJson" value="<?= $conditionsJson ?? '' ?>" />
        <input type="submit" name="btnsubmitSubmitConditions" value="Salvar condições" />
        <input type="submit" name="btnsubmitDeleteConditions" value="Excluir condições" />
    </div>

</form>