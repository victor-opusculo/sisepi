<?php 
if(count($dataRows) > 0) 
{
?>

<div class="viewDataFrame">
	<label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", $listInfosDataRow["eventId"]); ?>"><?php echo hsc($listInfosDataRow["name"]); ?></a> <br/>
	<label>Encerramento da lista: </label><?php echo date_format(date_create($listInfosDataRow["subscriptionListClosureDate"]), "d/m/Y"); ?> <br/>
	<label>Vagas: </label><?php echo $listInfosDataRow["maxSubscriptionNumber"]; ?> (<?php echo count($dataRows); ?> ocupadas)
</div>
<br/>
<?php $dgComp->render() ?>

<?php } 
else
{
	echo "Lista vazia.";
}
?>