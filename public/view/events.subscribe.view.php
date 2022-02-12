<?php 

if (!empty($_GET["messages"])) {?>

<?php }
else
{

if($isValidEventId && $eventObj->subscriptionListNeeded)
{	
	if ($passedClosureDate) { ?>
		<div class="centControl">Desculpe, as inscrições para este evento foram encerradas.</div>
<?php } else if ($isListFull) { ?>
		<div class="centControl">Desculpe, todas as vagas deste evento foram preenchidas.</div>
<?php } else { ?>

<?php if($eventObj->posterImageAttachmentFileName): ?>
<div class="centControl responsiveImageFrame">
	<img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("uploads/events/$eventObj->id/$eventObj->posterImageAttachmentFileName"); ?>" alt="<?php echo hscq($eventObj->name); ?>" style="border-radius: 10px;"/> 
</div>
<?php endif; ?>
<?php
	$subscriptionPolicyLink = $subscriptionPolicyFile;
	$consentFormLink = $consentFormFile;
?>

<script>
	const getLastSubscriptionScriptURL = '<?php echo URL\URLGenerator::generateFileURL("generate/getLastSubscriptionData.php", "email={email}"); ?>';
</script>
<script src="<?php echo URL\URLGenerator::generateFileURL("view/events.subscribe.view.js"); ?>"></script>

<div class="viewDataFrame">
	<label>Evento: </label><?php echo hsc($eventObj->name); ?> <br/>
	<label>Tipo: </label><?php echo hsc($eventObj->typeName); ?> <br/>
	<label>Vagas: </label><?php echo $eventObj->maxSubscriptionNumber; ?>
</div>

<form id="frmSubs" action="<?php echo URL\URLGenerator::generateFileURL("post/subscribe.post.php", "cont=events&action=subscribe&eventId=$eventObj->id"); ?>" method="post" style="margin-top: 30px;">
	<input type="hidden" name="eventId" value="<?php echo $eventObj->id; ?>"/>
	<span class="formField"><label>E-mail: <input id="txtEmail" type="email" name="txtEmail" size="60" maxLength="80" required="required"/></label></span>
	
	<h3>Política de Inscrição e Certificação e Termo de Consentimento</h3>
	
	<span class="formField">Você concorda nossa política de inscrição e certificação? (Leia <a href="<?php echo $subscriptionPolicyLink; ?>">aqui</a>)<br/> <label><input type="checkbox" name="chkAgreesWithSubsPolicy" value="1" required="required"/>Concordo</label></span>
	<span class="formField">Aceita receber por e-mail informações dos eventos da Escola do Parlamento "Doutor Osmar de Souza"? <br/><label><input type="checkbox" name="chkSubscribeMailing" value="1"/>Aceito</label></span>
	<span class="formField">Você concorda com o termo de consentimento para o tratamento de seus dados pessoais? (Leia <a href="<?php echo $consentFormLink; ?>">aqui</a>) <br/><label><input type="checkbox" name="chkAgreesWithConsentForm" value="1" required="required"/>Concordo</label></span>
	<input type="hidden" name="consentFormLink" value="<?php echo $consentFormLink; ?>"/>
	
	<h3>Inscrição</h3>
	
	<span class="formField"><label>Nome completo: <input type="text" name="txtName" size="60" maxLength="80" required="required"/></label></span>
	<span class="formField"><label><input type="checkbox" name="chkUseSocialName" id="chkUseSocialName"/>Usar nome social no certificado</label></span>
	<span class="formField" id="socialNameFrame" style="display: none;">
		<label>Nome social: <input type="text" size="60" maxLength="80" name="txtSocialName"/></label>
		<p style="color: red; font-size: 80%;">O nome social será exibido no certificado juntamente ao nome completo fornecido acima. Só preencha este campo se você tiver nome social e ele for diferente do nome completo. </p>
	</span>
	<span class="formField"><label>Celular (com prefixo): <input type="text" size="20" maxLength="20" name="txtTelephone" required="required"/></label></span>
	<span class="formField"><label>Data de nascimento: <input type="date" id="dateBirthDate" name="dateBirthDate" required="required"/></label></span>
	<span class="formField"><span style="font-weight: bold;">Gênero:</span> 
		<?php foreach ($genderList as $g): ?>
			<br/><label><input type="radio" name="radGender" required="required" value="<?php echo $g; ?>"/><?php echo $g; ?></label>
		<?php endforeach; ?>
	</span>
	<span class="formField"><span style="font-weight: bold;">Nacionalidade:</span>
		<?php foreach ($nationalityList as $n): ?>
			<br/><label><input type="radio" name="radNationality" required="required" value="<?php echo $n; ?>"/><?php echo $n; ?></label>
		<?php endforeach; ?>
	</span>
	<span class="formField"><span style="font-weight: bold;">Etnia:</span>
		<?php foreach ($raceList as $r): ?>
			<br/><label><input type="radio" name="radRace" required="required" value="<?php echo $r; ?>"/><?php echo $r; ?></label>
		<?php endforeach; ?>
	</span>
	<span class="formField"><span style="font-weight: bold;">Escolaridade:</span> 
		<?php foreach ($schoolingTypesList as $s): ?>
			<br/><label><input type="radio" name="radSchoolingLevel" required="required" value="<?php echo $s; ?>"/><?php echo $s; ?></label>
		<?php endforeach; ?>
	</span>
	<span class="formField"><span style="font-weight: bold;">Estado (UF):</span>
		<select name="selUF" required="required">
			<?php foreach ($stateList as $s): ?>
				<option value="<?php echo $s; ?>"><?php echo $s; ?></option>
			<?php endforeach; ?>
		</select>
	</span>
	<span class="formField"><span style="font-weight: bold;">Área de atuação principal:</span> 
		<?php foreach($occupationTypesList as $o): ?>
			<br/><label><input type="radio" name="radOccupation" required="required" value="<?php echo $o; ?>"/><?php echo $o; ?></label>
		<?php endforeach; ?>
	</span>
	<span class="formField">
		<span style="font-weight: bold;">Necessita de alguma adaptação ou recurso de acessibilidade para participar do evento?</span>
		<br/><label><input type="radio" name="radAccessibilityRequired" value="0" required="required">Não</label>
		<br/><label><input type="radio" name="radAccessibilityRequired" value="1">Sim: </label><input type="text" size="40" maxLength="60" name="txtAccessibilityRequired" placeholder="qual?"/>
	</span>
	<br/>
	<div class="centControl">
		<input type="submit" id="btnsubmitSubmitSubscription" name="btnsubmitSubmitSubscription" value="Enviar"/>
	</div>
</form>

<?php } 
}  ?>
<?php 
} ?>