<?php if (isset($odsData, $odsRelation)): ?>
    <?php 
        function getOdsDescription($number, $odsData)
        {
            array_filter($odsData, fn($o) => $o->number == $number);
        }  
    ?>
    <div class="viewDataFrame">
        
        <fieldset>
            <legend>Evento vinculado</legend>
            <?php if (!empty($odsRelation->eventId)): ?>
                <label>Nome: </label>
                <a href="<?= URL\URLGenerator::generateSystemURL('events', 'view', $odsRelation->eventId) ?>">
                    <?= hsc($odsRelation->getOtherProperties()->eventName) ?>
                </a>
            <?php else: ?>
                <em>Nenhum</em>
            <?php endif; ?>
        </fieldset>
        <fieldset>
            <legend>Relação ODS</legend>
            <label>Nome: </label><?= hsc($odsRelation->name) ?> <br/>
            <label>Exercício: </label><?= hsc($odsRelation->year) ?> <br/>

            <h3>Metas ODS</h3>
            <?php foreach ($odsRelation->odsAndGoalsStructured as $number => $ods): ?>
                <h4><?= "{$number}. " . hsc($ods['description']) ?></h4>
                <ul>
                    <?php foreach ($ods['goals'] as $id => $goal): ?>
                        <li><?= "<strong>{$number}.{$id}</strong> - " . hsc($goal['description']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>

        </fieldset>
    </div>

    <div class="editDeleteButtonsFrame">
        <ul>
            <li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("odsrelations", "edit", $odsRelation->id); ?>">Editar</a></li>
            <li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("odsrelations", "delete", $odsRelation->id); ?>">Excluir</a></li>
        </ul>
    </div>
<?php endif; ?>