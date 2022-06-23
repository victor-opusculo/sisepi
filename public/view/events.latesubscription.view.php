<?php
	$subscriptionPolicyLink = $subscriptionPolicyFile;
	$consentFormLink = $consentFormFile;
?>
	
<?php if ($eventObj !== null && isset($_GET["messages"])) { ?>

	<?php if (isset($_GET["backToPresenceListEventId"]) && is_numeric($_GET["backToPresenceListEventId"])): ?>
	<div class="centControl">
		<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("events", "signpresencelist", null, ['eventDateId' => $_GET["backToPresenceListEventId"] ]); ?>">Voltar à lista de presença</a>
	</div>
	<?php endif; ?>
<?php	} ?>

<?php if ($eventObj !== null && $showForm) { ?>

<script>
	window.onload = function()
	{
		document.getElementById("chkUseSocialName").onchange = function(e)
		{
			document.getElementById("spanSocialName").style.display = this.checked ? "block" : "none";
		};
	};
</script>

<div class="viewDataFrame">
	<label>Evento: </label><?php echo hsc($eventObj->name); ?> <br/>
	<label>Tipo: </label><?php echo hsc($eventObj->typeName); ?> <br/>
	<label>Vagas: </label><?php echo $eventObj->maxSubscriptionNumber; ?>
</div>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/latesubscription.post.php", "cont=events&action=latesubscription&eventId=$eventObj->id&backToPresenceListEventId=" . $_GET["backToPresenceListEventId"]); ?>" method="post">
	<h3>Inscrição</h3>
	
	<span class="formField"><label>E-mail: <input type="email" name="txtEmail" size="60" maxlength="80" required="required"/></label></span>
	<span class="formField"><label>Nome completo: <input type="text" name="txtName" size="60" maxlength="80" required="required"/></label></span>
	<span class="formField"><label><input type="checkbox" id="chkUseSocialName"/> Usar nome social no certificado</label></span>
	<span class="formField" id="spanSocialName" style="display:none;">
		<label>Nome social: <input type="text" name="txtSocialName" size="60" maxlength="80"/></label>
		<p style="color: red; font-size: 80%;">O nome social será exibido no certificado juntamente ao nome completo fornecido acima. Só preencha este campo se você tiver nome social e ele for diferente do nome completo. </p>
	</span>
	
	<h3>Política de Inscrição e Certificação e Termo de Consentimento</h3>
	
	<span class="formField">Você concorda nossa política de inscrição e certificação? (Leia <a href="<?php echo $subscriptionPolicyLink; ?>">aqui</a>)<br/> <label><input type="checkbox" name="chkAgreesWithSubsPolicy" value="1" required="required"/>Concordo</label></span>
	<span class="formField">Você concorda com o termo de consentimento para o tratamento de seus dados pessoais? (Leia <a href="<?php echo $consentFormLink; ?>">aqui</a>) <br/><label><input type="checkbox" name="chkAgreesWithConsentForm" value="1" required="required"/>Concordo</label></span>
	<input type="hidden" name="consentFormVersion" value="<?php echo $consentFormVersion; ?>"/>
	
	<input type="submit" name="btnsubmitSubmitLateSubscription" value="Enviar" />
	<input type="hidden" name="eventId" value="<?php echo $eventObj->id; ?>"/>
</form>

<?php } ?>