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
	
		<?php if (checkUserPermission("EVENT", 7)): ?>
		<label>Nome: </label><input type="text" size="60" maxlength="110" name="txtName" value="<?php echo hscq($subsObj->name); ?>" /> <br/>
		<label>Nome social: </label><input type="text" size="60" maxlength="110" name="txtSocialName" value="<?php echo hscq($subsObj->socialName); ?>" /> 
		<input type="submit" name="btnsubmitSubmit" value="Alterar nomes"/>
		<?php else: ?>
		<label>Nome: </label><?php echo hsc($subsObj->name); ?> <br/>
		<label>Nome social: </label><?php echo hsc($subsObj->socialName); ?>
		<?php endif; ?><br/>
		
	<label>E-mail: </label><?php echo hsc($subsObj->email); ?> <br/>
	<label>Telefone: </label><?php echo hsc($subsObj->telephone); ?> <br/>
	<label>Data de nascimento: </label><?php if ($subsObj->birthDate) echo date_format(date_create($subsObj->birthDate), "d/m/Y"); ?> <br/>
	<label>Gênero: </label><?php echo hsc($subsObj->gender); ?> <br/>
	<label>Nacionalidade: </label><?php echo hsc($subsObj->nationality); ?> <br/>
	<label>Etnia: </label><?php echo hsc($subsObj->race); ?> <br/>
	<label>Escolaridade: </label><?php echo hsc($subsObj->schoolingLevel); ?> <br/>
	<label>Estado (UF): </label><?php echo hsc($subsObj->stateUf); ?> <br/>
	<label>Área de atuação: </label><?php echo hsc($subsObj->occupation); ?> <br/>
	<label>Recurso de acessibilidade requerido: </label><?php echo hsc($subsObj->accessibilityFeatureNeeded); ?> <br/>
	<label>Termo de consentimento para tratamento de dados pessoais: </label><?php echo $subsObj->consentForm; ?> <br/>
	<label>Concorda com o termo? </label><?php echo ($subsObj->agreesWithConsentForm ? "Concorda" : "Não concorda"); ?> <br/>
	<label>Data de inscrição: </label><?php echo date_format(date_create($subsObj->subscriptionDate), "d/m/Y H:i:s"); ?> <br/>

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