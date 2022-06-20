
<?php if (isset($eventInfos)): ?>
<div class="viewDataFrame">
    <label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('events', 'view', $eventInfos->id); ?>"><?php echo hsc($eventInfos->name); ?></a> <br/>
    <label>Tipo: </label><?php echo hsc($eventInfos->typeName); ?> <br/>
    <label>Início: </label><?php echo date_create($eventInfos->beginDate)->format("d/m/Y"); ?> <br/>
    <label>Encerramento: </label><?php echo date_create($eventInfos->endDate)->format("d/m/Y"); ?> <br/>
    <br/>
    <label>Número de pesquisas respondidas: </label><?php echo count($surveyList); ?>
</div>

<?php $dgComp->render(); ?>

<br/>
<div class="rightControl">
    <a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/eventsurveystocsv.php", [ 'eventId' => $eventInfos->id ]); ?>">Exportar para CSV</a>
</div>

<?php endif; ?>