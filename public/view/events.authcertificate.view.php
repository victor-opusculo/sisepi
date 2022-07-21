<?php if ($showData): ?>

<div class="viewDataFrame">
	<?php if ($certDataRow): ?>
		<div style="color: green; text-align: center; font-weight: bold; font-size: x-large; margin-bottom: 20px;">
			<span>Certificado válido!</span>
		</div>
		<label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", $certDataRow["eventId"]); ?>"><?php echo hsc($certDataRow["eventName"]); ?></a> <br/>
		
		<?php if ($studentDataRow) { ?>
		<label>Nome do participante: </label><?php echo hsc($studentDataRow["name"]) . (!empty($studentDataRow["socialName"]) ? " (" . hsc($studentDataRow["socialName"]) . ")" : ""); ?> <br/>
		<label>Presença no evento: </label><?php echo $studentDataRow["presencePercent"]; ?>% 
		<?php } else { ?>
		<p>Os dados deste participante foram excluídos da base de dados, mas o certificado foi emitido e é válido.</p>
		<?php } ?>
		<br/> <br/>
		
		<label>Código do certificado: </label><?php echo $certDataRow["id"]; ?> <br/>
		<label>Emissão do certificado: </label><?php echo date_format(date_create($certDataRow["dateTime"]), "d/m/Y H:i:s"); ?>
	<?php else: ?>
		<div style="color: red; text-align: center; font-weight: bold; font-size: x-large;">
			<span>Certificado inválido ou não existente!</span>
		</div>
	<?php endif; ?>
</div>

<?php else: ?>
<form method="get">
	<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
		<input type="hidden" name="cont" value="events"/>
		<input type="hidden" name="action" value="authcertificate"/>
	<?php endif; ?>
	<div class="centControl">Informe os dados que estão no verso do certificado.</div>
	<span class="formField"><label>Código: <input type="number" min="1" value="" name="code" required="required"/></label></span>
	<span class="formField"><label>Data e horário de emissão: <input type="date" name="date" required="required"/><input type="time" name="time" step="1" required="required"/></label></span>
	<span class="formField"><input type="submit" value="Verificar"/></span>
</form>
<?php endif; ?>