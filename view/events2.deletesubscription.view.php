<?php if (!empty($_GET["messages"])) { ?>

<?php } else { ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/deletesubscription.post.php", "cont=events2&action=deletesubscription&id=$subsObj->id"); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir esta inscrição? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
		<label>Nome: </label><?php echo hsc($subsObj->name); ?><br/>
		<label>Data de inscrição: </label><?php echo date_format(date_create($subsObj->subscriptionDate), "d/m/Y H:i:s"); ?><br/><br/>
		<input type="hidden" name="subsId" value="<?php echo $subsObj->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteSubscription" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="history.back();"/>
	</div>
	
</form>

<?php
}
?>