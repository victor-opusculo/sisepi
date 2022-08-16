<?php if ($showData): ?>

<div class="viewDataFrame">
	<?php if ($signDataRow): ?>
		<div style="color: green; text-align: center; font-weight: bold; font-size: x-large; margin-bottom: 20px;">
			<span>Assinatura válida!</span>
		</div>

        <label>Docente: </label><?php echo hsc($signDataRow["professorName"]); ?> <br/>
        <?php if ($signDataRow['docTemplateJson']): ?>
		<label>Documento assinado: </label><?php echo hsc($signDocumentName); ?> <br/>
		<?php endif; ?>
        <br/>
        <?php $activityObj = json_decode($signDataRow['participationEventDataJson']); ?>
		<label>Atividade exercida: </label><?php echo hsc($activityObj->activityName ?? ''); ?><br/>
        <label>Datas: </label><?php echo hsc($activityObj->dates ?? ''); ?><br/>
        <label>Horários: </label><?php echo hsc($activityObj->times ?? ''); ?><br/>
        <label>Carga horária: </label><?php echo hsc($activityObj->workTime ?? ''); ?> 

		<br/> <br/>
		
		<label>Código da assinatura: </label><?php echo $signDataRow["id"]; ?> <br/>
		<label>Data e horário da assinatura: </label><?php echo date_format(date_create($signDataRow["signatureDateTime"]), "d/m/Y H:i:s"); ?>
	<?php else: ?>
		<div style="color: red; text-align: center; font-weight: bold; font-size: x-large;">
			<span>Assinatura inválida ou não existente!</span>
		</div>
	<?php endif; ?>
</div>

<?php else: ?>
<form method="get">
	<?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
		<input type="hidden" name="cont" value="professors"/>
		<input type="hidden" name="action" value="authsignature"/>
	<?php endif; ?>
	<div class="centControl">Informe os dados que estão no campo da assinatura.</div>
	<span class="formField"><label>Código: <input type="number" min="1" value="" name="code" required="required"/></label></span>
	<span class="formField"><label>Data e horário de assinatura: <input type="date" name="date" required="required"/><input type="time" name="time" step="1" required="required"/></label></span>
	<span class="formField"><input type="submit" value="Verificar"/></span>
</form>
<?php endif; ?>