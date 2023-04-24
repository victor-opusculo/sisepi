
<?php 
if (isset($budgetEntry)):

function writeSelectedStatus($property, $expectedValue)
{
    return (string)$property === (string)$expectedValue ? ' selected ' : '';
}?>

<form method="post" action="<?= URL\URLGenerator::generateFileURL('post/budget.edit.post.php', [ 'cont' => $_GET['cont'], 'action' => $_GET['action'], 'id' => $_GET['id'] ]) ?>">
    <span class="formField">
        <label>Data prevista: <input type="date" name="budgetentries:dateDate" required value="<?= hscq($budgetEntry->date) ?>"/></label>
    </span>
    <span class="formField">
        <label>Categoria: 
            <select name="budgetentries:selCategory">
                <?php if (isset($budgetCats)): foreach ($budgetCats as $cat): ?>
                    <option value="<?= $cat->id ?>" <?= writeSelectedStatus($budgetEntry->category, $cat->id) ?>><?= hsc($cat->value) ?></option>
                <?php endforeach; endif; ?>
            </select>
        </label>
    </span>
    <span class="formField">
        <label>Detalhes/Descrição: <input type="text" name="budgetentries:txtDetails" size="40" maxlength="254" value="<?= hscq($budgetEntry->details) ?>"/></label>
    </span>
    <span class="formField">
        <label>Valor: <input type="number" name="budgetentries:numValue" min="0" step="any" value="<?= hscq(abs($budgetEntry->value)) ?>"/></label>
        <label><input type="radio" name="radEntryType" value="0" <?= $budgetEntry->value >= 0 ? 'checked' : '' ?>/>Receita</label> 
        <label><input type="radio" name="radEntryType" value="1" <?= $budgetEntry->value < 0 ? 'checked' : '' ?>/>Despesa</label>
    </span>
    <br/>

    <fieldset>
        <legend>Entidades vinculadas</legend>

        <span style="display: flex; align-items: center;">
            <label>Evento ID: 
                <input type="number" id="numEventId" name="budgetentries:numEventId" min="1" step="1" value="<?= hscq($budgetEntry->eventId) ?>"/>
            </label>
            <button type="button" id="btnLoadEvent" style="min-width:20px;" ><?php echo htmlspecialchars(">"); ?></button>
            <button type="button" id="btnSearchEvent"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
        </span>
        <span class="formField">
            <label>Nome: </label><span id="lblEventName"></span> <br/>
        </span>

        <br/>

        <span style="display: flex; align-items: center;">
            <label>Ficha de trabalho ID: 
                <input type="number" id="numWorkSheetId" name="budgetentries:numProfWorkSheetId" min="1" step="1" value="<?= hscq($budgetEntry->professorWorkSheetId) ?>"/>
            </label>
            <button type="button" id="btnLoadWorkSheet" style="min-width:20px;" ><?php echo htmlspecialchars(">"); ?></button>
            <button type="button" id="btnSearchWorkSheet"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
        </span>
        <span class="formField">
            <label>Atividade: </label><span id="lblWorkSheetActivityName"></span> <br/>
            <label>Valor do empenho: </label><span id="lblWorkSheetPaymentValue"></span> <br/>
        </span>

    </fieldset>
    <div class="centControl">
        <input type="hidden" name="budgetentries:budgetEntryId" value="<?= $budgetEntry->id ?>"/>
        <input type="submit" name="btnsubmitSubmitBudgetEntry" value="Editar dotação"/>
    </div>
</form>

<script src="<?= URL\URLGenerator::generateFileURL('view/fragment/eventByIdLoader.js') ?>"></script>
<script src="<?= URL\URLGenerator::generateFileURL('view/fragment/profWorkSheetByIdLoader.js') ?>"></script>
<script>
    setUpEventByIdLoader
    ({
        setData: data => document.getElementById('lblEventName').innerText = data.name,
        setId: id => document.getElementById('numEventId').value = id,
        getId: () => document.getElementById('numEventId').value,
        buttonLoad: document.getElementById('btnLoadEvent'),
        buttonSearch: document.getElementById('btnSearchEvent')
    });

    setUpWorkSheetByIdLoader
    ({
        setData: data => 
        {
            document.getElementById('lblWorkSheetActivityName').innerText = data.participationEventDataJson.activityName || "(Indefinido)";
            document.getElementById('lblWorkSheetPaymentValue').innerText = "R$ " + Number(data.paymentValue || 0).toLocaleString("pt-BR", { minimumFractionDigits: 2 });
        },
        setId: id => document.getElementById('numWorkSheetId').value = id,
        getId: () => document.getElementById('numWorkSheetId').value,
        buttonLoad: document.getElementById('btnLoadWorkSheet'),
        buttonSearch: document.getElementById('btnSearchWorkSheet')
    });
</script>

<?php endif; ?>