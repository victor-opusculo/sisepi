
<?php if ($resObj !== null) { ?>
<div class="viewDataFrame">
	<label>ID: </label><?php echo $resObj->id; ?> <br/>
	<label>Publicação reservada: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("librarycollection", "view", $resObj->publicationId); ?>"><?php echo hsc($resObj->title); ?></a> <br/>
	<label>Usuário: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("libraryusers", "view", $resObj->libUserId); ?>"><?php echo hsc($resObj->userName); ?></a> <br/>
	<label>Data da reserva: </label><?php echo date_format(date_create($resObj->reservationDatetime), "d/m/Y H:i:s"); ?> <br/>
	<label>Empréstimo resultante: </label>
		<?php if ($resObj->borrowedPubId): ?>
		<a href="<?php echo URL\URLGenerator::generateSystemURL("libraryborrowedpubs", "view", $resObj->borrowedPubId); ?>">Código <?php echo $resObj->borrowedPubId; ?></a> 
		<?php endif; ?><br/>
	<label>Invalidada em: </label><?php if ($resObj->invalidatedDatetime) echo date_format(date_create($resObj->invalidatedDatetime), "d/m/Y H:i:s"); ?> <br/>
	<label>Atendida? </label>
		<?php if ($resObj->borrowedPubId): ?>
		<img src="<?php echo URL\URLGenerator::generateFileURL("pics/check.png"); ?>" alt="Sim" />
		<?php elseif ($resObj->invalidatedDatetime): ?>
		<img src="<?php echo URL\URLGenerator::generateFileURL("pics/wrong.png"); ?>" alt="Invalidada" />
		<?php else: ?>
		<span>Aguardando</span>
		<?php endif; ?>
</div>
<div class="editDeleteButtonsFrame">
	<ul>
		<li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("libraryreservations", "delete", $resObj->id); ?>">Excluir</a></li>
		<?php if (!$resObj->borrowedPubId && !$resObj->invalidatedDatetime): ?>
		<li><a id="linkBorrow" href="<?php echo URL\URLGenerator::generateSystemURL("libraryborrowedpubs", "create", null, [ 'pubId' => $resObj->publicationId, 'userId' => $resObj->libUserId ]); ?>">Finalizar reserva (Emprestar)</a></li>
		<?php endif; ?>
	</ul>
</div>

<?php } ?>