<?php if($eventObj !== null) { ?>

<div class="viewDataFrame">
	<label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", (string)$eventObj->id); ?>"><?php echo hsc($eventObj->name); ?></a> <br/>
    <label>Tipo: </label><?php echo hsc($eventObj->typeName); ?> <br/>
    <label>Início: </label><?php echo date_format(date_create($eventObj->beginDate), "d/m/Y"); ?> <br/>
	<label>Encerramento: </label><?php echo date_format(date_create($eventObj->endDate), "d/m/Y"); ?> <br/>
	<br/>
    <label>Número de certificados emitidos: </label><?php echo $certsCount; ?>
</div>
<br/>
<?php $dgComp->render(); 
}
?>