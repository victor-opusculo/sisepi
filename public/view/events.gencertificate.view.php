<?php if ($eventDataRow) { ?>

<div class="viewDataFrame">
	<label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", $eventDataRow["id"]); ?>"><?php echo hsc($eventDataRow["name"]); ?></a><br/>
</div>
<br/>

<?php if (!$emailToGenerateCert) { ?>
<?php if ($eventDataRow["certificateText"]) { ?>
<?php if ($isEventOver) { ?> 
<form action="<?php echo URL\URLGenerator::generateSystemURL("events", "gencertificate", null, [ 'eventId' => $eventDataRow["id"] ] ); ?>" method="post" >
	<span class="formField"><label>E-mail fornecido na inscrição ou lista de presença: <br/>
		<input type="email" name="txtEmail" size="50" maxlength="80" required="required"/></label>
	</span>
	<span class="formField"><input type="submit" name="btnsubmitGenCert" value="Gerar certificado" /></span>
</form>
<?php } else { ?>
	<div class="centControl">
		<p>Este evento ainda não terminou.</p>
	</div>
<?php } } else { ?>
	<div class="centControl">
		<p>Este evento não fornece certificados automaticamente.</p>
	</div>
<?php } 
} ?>

<?php if ($emailToGenerateCert) { ?>
<form id="frmDataToGenerateCertificate" action="<?php echo URL\URLGenerator::generateFileURL("generate/generateCertificate.php"); ?>" method="get">
	<input type="hidden" name="eventId" value="<?php echo $eventDataRow["id"]; ?>"/>
	<input type="hidden" name="email" value="<?php echo $emailToGenerateCert; ?>"/>
	<noscript>
		<label>Clique no botão abaixo para abrir o certificado:</label><br/>
		<input type="submit" value="Abrir PDF"/>
	</noscript>
</form>
<script type="text/javascript">
	document.getElementById("frmDataToGenerateCertificate").submit();
</script>
<?php } ?>

<?php } else { ?>
	<div class="centControl">
		<p>Evento não localizado.</p>
	</div>
<?php } ?>