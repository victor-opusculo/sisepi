<?php if($dataRows && count($dataRows) > 0) 
{
?>

<div class="viewDataFrame">
	<label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view",  $listInfosDataRow["eventId"]); ?>"><?php echo hsc($listInfosDataRow["name"]); ?></a> <br/>
	<label>Encerramento da lista: </label><?php echo date_format(date_create($listInfosDataRow["subscriptionListClosureDate"]), "d/m/Y"); ?> <br/>
	<label>Vagas: </label><?php echo $listInfosDataRow["maxSubscriptionNumber"]; ?> (<?php echo $listCount; ?> ocupadas)
</div>
<br/>
<?php $dgComp->render() ?>

<br/>
<div class="rightControl">
	<a class="linkButton" href="<?php echo URL\URLGenerator::generateFileURL("generate/subscriptionstocsv.php", [ 'eventId' => $listInfosDataRow['eventId'] ]); ?>">Exportar para CSV</a>
</div>
<?php
}
else
{
	echo "Lista vazia.";
}
?>