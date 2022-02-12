
<?php if ($resObj !== null) { ?>

<form action="<?php echo URL\URLGenerator::generateFileURL('post/deletelibreservation.post.php', "cont=libraryreservations&action=delete&id=$resObj->id"); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir esta reserva da biblioteca? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
		<input type="hidden" name="reservationId" value="<?php echo $resObj->id; ?>"/>
		<label>ID: </label><?php echo $resObj->id; ?> <br/>
		<label>Publicação reservada: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("librarycollection", "view", $resObj->publicationId); ?>"><?php echo hsc($resObj->title); ?></a> <br/>
		<label>Usuário: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("libraryusers", "view", $resObj->libUserId); ?>"><?php echo hsc($resObj->userName); ?></a> <br/>
		<label>Data da reserva: </label><?php echo date_format(date_create($resObj->reservationDatetime), "d/m/Y H:i:s"); ?> <br/>
		<label>Atendida? </label>
			<?php if ($resObj->borrowedPubId): ?>
			<img src="<?php echo URL\URLGenerator::generateFileURL("pics/check.png"); ?>" alt="Sim" />
			<?php elseif ($resObj->invalidatedDatetime): ?>
			<img src="<?php echo URL\URLGenerator::generateFileURL("pics/wrong.png"); ?>" alt="Invalidada" />
			<?php else: ?>
			<span>Aguardando</span>
			<?php endif; ?>
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteReservation" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="window.location.href = '<?php echo URL\URLGenerator::generateSystemURL("libraryreservations"); ?>';"/>
	</div>
</form>

<?php } ?>