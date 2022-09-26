<?php if($subsObj !== null) 
{
?>

<?php if (checkUserPermission("EVENT", 7)): ?>
<form action="<?php echo URL\URLGenerator::generateFileURL("post/editsubscriptionnames.post.php", "cont=events2&action=viewsubscription&id=$subsObj->id"); ?>" method="post">
<input type="hidden" name="subscriptionId" value="<?php echo $subsObj->id; ?>" />
<?php endif; ?>

<div class="viewDataFrame">
	<?php $eventId = $eventInfoDataRow["eventId"]; ?>
	<label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", $eventId); ?>"><?php echo hsc($eventInfoDataRow["name"]); ?></a><br/>
	<br/>

	<h3>Dados de Inscrição</h3>
	<?php if (checkUserPermission("EVENT", 7)): ?>
		<label>Nome: </label><input type="text" maxlength="255" size="40" name="txtName" value="<?= $subsObj->name ?>" /> <br/>
		<label>E-mail: </label><input type="text" maxlength="255" size="40" name="txtEmail" value="<?= $subsObj->email ?>" /> <br/>
		<?php foreach ($subsObj->subscriptionDataJson->questions as $i => $question): ?>
			<label><?= $question->formInput->label ?> </label>
			<?php if ($question->formInput->type === 'date'): ?>
				<input type="date" name="questions[<?= $i ?>]" value="<?= $question->value ?? ''?>" /> <br/>
			<?php elseif ($question->formInput->type === 'info'): ?>
				<br/>
			<?php else: ?>
				<input type="text" name="questions[<?= $i ?>]" maxlengh="255" size="40" value="<?= $question->value ?? '' ?>" /> <br/>
			<?php endif; ?>
		<?php endforeach; ?>
		<input type="submit" name="btnsubmitSubmit" value="Alterar dados" /> <br/>
	<?php else: ?>
		<label>Nome: </label><?= $subsObj->name; ?> <br/>
		<label>E-mail: </label><?= $subsObj->email; ?> <br/>
		<?php foreach ($subsObj->subscriptionDataJson->questions as $question): ?>
			<label><?= $question->formInput->label ?> </label><?= ($question->formInput->type === 'date') ? date_create($question->value ?? '')->format('d/m/Y') : $question->value ?? ''?> <br/>
		<?php endforeach; ?>
	<?php endif; ?>
	<label>Data de inscrição: </label><?= date_create($subsObj->subscriptionDate)->format('d/m/Y H:i:s') ?>

	<h3>Termos</h3>
	<?php foreach ($subsObj->subscriptionDataJson->terms as $term): ?>
		<label><?= $term->name ?>: </label><?php echo $term->value == 1 ? 'Concorda' : 'Não concorda'; ?> (<a href="<?= URL\URLGenerator::generateFileURL("uploads/terms/{$term->termId}.pdf") ?>">Termo ID <?= $term->termId ?></a>) <br/>
	<?php endforeach; ?>

	<div class="editDeleteButtonsFrame">
		<ul>
			<li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("events2", "deletesubscription", $subsObj->id); ?>">Excluir</a></li>
		</ul>
	</div>
</div>

<?php if (checkUserPermission("EVENT", 7)): ?>
</form>
<?php endif; ?>

<?php } 
else
{
	echo "Registro não localizado";
}
?>