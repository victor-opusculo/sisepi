<?php require_once __DIR__ . "/../includes/GenerateView/EventTest.php"; ?>

<?php if (isset($eventObj, $templateObj) && empty($_GET['filled'])): ?> 
    <?php if (date_create(date('Y-m-d')) >= date_create($eventObj->getOtherProperties()->endDate)): ?> 
        <?php if (isset($studentInfos, $templateObj)): ?>

        <div class="viewDataFrame">
            <label>Evento: </label><a href="<?= URL\URLGenerator::generateSystemURL('events', 'view', $eventObj->id) ?>"><?= hsc($eventObj->name) ?></a><br/>
            <label>Tipo: </label><?= hsc($eventObj->getOtherProperties()->typeName) ?><br/>
            <label>Início: </label><?= date_create($eventObj->getOtherProperties()->beginDate)->format('d/m/Y') ?><br/>
            <label>Encerramento: </label><?= date_create($eventObj->getOtherProperties()->endDate)->format('d/m/Y') ?><br/>
            <label>E-mail de participante: </label><?= hsc($studentInfos->email) ?>
        </div>

        <form method="post" action="<?= URL\URLGenerator::generateFileURL('post/events2.filltest.post.php', [ 'cont' => $_GET['cont'], 'action' => $_GET['action'], 'goTo' => $_GET['goTo'] ?? '', 'eventId' => $eventObj->id, 'email' => $studentInfos->email ]) ?>">
            <div class="testBlock">
                <?php \SisEPI\Pub\Includes\GenerateView\EventTest\writeTestFormFieldsHTML(json_decode($templateObj->templateJson)); ?>
            </div>
            <div class="centControl">
                <input type="hidden" name="eventcompletedtests:hidEventId" value="<?= $eventObj->id ?>" />
                <input type="hidden" name="hidEmail" value="<?= hscq($studentInfos->email) ?>" />
                <input type="submit" name="btnsubmitSubmitTest" value="Enviar respostas" />
            </div>
        </form>

        <?php else: ?>

        <div class="viewDataFrame">
            <label>Evento: </label><a href="<?= URL\URLGenerator::generateSystemURL('events', 'view', $eventObj->id) ?>"><?= hsc($eventObj->name) ?></a><br/>
            <label>Tipo: </label><?= hsc($eventObj->getOtherProperties()->typeName) ?><br/>
            <label>Início: </label><?= date_create($eventObj->getOtherProperties()->beginDate)->format('d/m/Y') ?><br/>
            <label>Encerramento: </label><?= date_create($eventObj->getOtherProperties()->endDate)->format('d/m/Y') ?><br/>
        </div>
        <form method="get">
            <?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
                <input type="hidden" name="cont" value="events2" />
                <input type="hidden" name="action" value="filltest" />
            <?php endif; ?>
            <label>E-mail fornecido na inscrição ou lista de presença: <br/>
                <input type="email" name="email" size="40" />  
            </label>
            <input type="hidden" name="eventId" value="<?= $eventObj->id ?>" />
            <button type="submit">Entrar</button>
        </form>

        <?php endif; ?>
    <?php else: ?>
        <p class="centControl">Este evento ainda não terminou.</p>
    <?php endif; ?>

<?php endif; ?>
<?php if (isset($eventObj, $studentInfos) && !empty($_GET['filled']) && ((bool)$_GET['approved'] ?? false) && !empty($_GET['goTo'])): ?>
    <div class="centControl">
    <?php 
        require_once "controller/component/GoToButton.class.php";
        (new GoToButton(['actions' => $_GET['goTo'], 'queryString' => [ 'eventId' => $eventObj->id, 'email' => $studentInfos->email ] ]))->render();
    ?>
    </div>
<?php endif; ?>