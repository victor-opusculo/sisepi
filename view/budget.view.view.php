<?php if (isset($entryObj)): ?>

    <div class="viewDataFrame">
        <label>ID: </label><?= $entryObj->id ?><br/> 
        <label>Data prevista: </label><?= date_create($entryObj->date)->format('d/m/Y') ?><br/>
        <label>Tipo: </label><?= $entryObj->value >= 0 ? 'Receita' : 'Despesa' ?><br/>
        <label>Categoria: </label><?= hsc($entryObj->getOtherProperties()->categoryName) ?><br/>
        <label>Detalhes: </label><?= hsc($entryObj->details) ?><br/>
        <label>Valor: </label><span style="<?= $entryObj->value >= 0 ? 'color:green;' : 'color:red;' ?>"><?= hsc(formatDecimalToCurrency($entryObj->getOtherProperties()->absValue)) ?></span><br/>

        <br/>

        <fieldset>
            <legend>Entidades vinculadas</legend>
            <label>Evento: </label><a href="<?= URL\URLGenerator::generateSystemURL('events', 'view', $entryObj->eventId) ?>"><?= hsc($entryObj->getOtherProperties()->eventName) ?></a><br/>
            <label>Ficha de trabalho de docente: </label><a href="<?= URL\URLGenerator::generateSystemURL('professors2', 'viewworksheet', $entryObj->professorWorkSheetId) ?>"><?= hsc($entryObj->getOtherProperties()->profWorkSheetActivityName) ?></a>
        </fieldset>

        <div class="editDeleteButtonsFrame">
            <ul>
                <li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("budget", "edit", $entryObj->id); ?>">Editar</a></li>
                <li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("budget", "delete", $entryObj->id); ?>">Excluir</a></li>
            </ul>
	    </div>

    </div>

<?php endif; ?>