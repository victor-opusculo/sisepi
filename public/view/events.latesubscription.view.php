<?php if ($eventObj !== null && isset($_GET["messages"])) { ?>

	<?php if (isset($_GET["backToPresenceListEventId"]) && is_numeric($_GET["backToPresenceListEventId"])): ?>
	<div class="centControl">
		<a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("events", "signpresencelist", null, ['eventDateId' => $_GET["backToPresenceListEventId"] ]); ?>">Voltar à lista de presença</a>
	</div>
	<?php endif; ?>
<?php	} ?>

<?php if ($eventObj !== null && $showForm) { ?>

<div class="viewDataFrame">
	<label>Evento: </label><?php echo hsc($eventObj->name); ?> <br/>
	<label>Tipo: </label><?php echo hsc($eventObj->typeName); ?> <br/>
	<label>Vagas: </label><?php echo $eventObj->maxSubscriptionNumber; ?>
</div>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/latesubscription.post.php", "cont=events&action=latesubscription&eventId=$eventObj->id&backToPresenceListEventId=" . $_GET["backToPresenceListEventId"]); ?>" method="post">
	<h3>Inscrição</h3>
	
	<span class="formField"><label>E-mail: <input type="email" name="txtEmail" size="60" maxlength="80" required="required"/></label></span>
	<span class="formField"><label>Nome completo: <input type="text" name="txtName" size="60" maxlength="80" required="required"/></label></span>
	
	<?php
		require_once __DIR__ . '/../includes/GenerateView/EventSubscription.php';
		\GenerateView\EventSubscription\writeSubscriptionFormFieldsHTML($subscriptionTemplate, null, true);
	?>
	
	<input type="submit" name="btnsubmitSubmitLateSubscription" value="Enviar" />
	<input type="hidden" name="eventId" value="<?php echo $eventObj->id; ?>"/>
</form>

<?php } ?>