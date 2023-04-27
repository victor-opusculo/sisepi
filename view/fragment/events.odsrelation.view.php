<?php $odsRelation = $eventObj->odsRelation ?? null;
if (isset($odsRelation)): ?>

    <div class="viewDataFrame">
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
    </div>
    <div class="editDeleteButtonsFrame">
        <ul>
            <li><a id="linkEdit" href="<?php echo URL\URLGenerator::generateSystemURL("odsrelations", "edit", $odsRelation->id); ?>">Editar</a></li>
            <li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("odsrelations", "delete", $odsRelation->id); ?>">Excluir</a></li>
        </ul>
    </div>

<?php else: ?>
    <p>Não há metas ODS relacionadas a este evento.</p>
    <div class="rightControl">
        <a class="linkButton" href="<?= URL\URLGenerator::generateSystemURL('odsrelations', 'create', null, [ 'eventId' => $eventObj->id ]) ?>">Nova relação ODS</a>
    </div>
<?php endif; ?>