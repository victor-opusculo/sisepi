
<?php if (!empty($eventObj->budgetEntries)): ?>

    <?php $dgBudgetEntries->render(); ?>
    <br/>
    <div class="rightControl">
        Saldo do evento: <span style="color: <?= $budgetBalance >= 0 ? 'green' : 'red' ?>"><?= ($budgetBalance >= 0 ? '+' : '') . formatDecimalToCurrency($budgetBalance) ?></span>
    </div>
<?php else: ?>
    <p>Não há dotações para este evento.</p>
<?php endif; ?>
<div class="rightControl">
    <a class="linkButton" href="<?= URL\URLGenerator::generateSystemURL('budget', 'create', null, [ 'eventId' => $eventObj->id ]) ?>">Nova dotação</a>
</div>