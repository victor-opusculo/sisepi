<script>
	window.onload = function()
	{
		document.getElementById("chkApprovedOnly").onchange = function(e)
		{
			document.getElementById("frmCheckbox").submit();
		};
	};
</script>

<div class="viewDataFrame" style="margin-bottom: 20px;">
	<?php $eventId = $eventInfos["eventId"]; ?>
	<label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", $eventId); ?>"><?php echo hsc($eventInfos["name"]); ?></a>
</div>

<form id="frmCheckbox" method="get">
	<div class="rightControl">
		<?php if (URL\URLGenerator::useFriendlyURL === false): ?>
			<input type="hidden" name="cont" value="events2"/>
			<input type="hidden" name="action" value="viewpresencesapp"/>
		<?php endif; ?>
		<label><input type="checkbox" id="chkApprovedOnly" name="approvedOnly" value="1" <?php echo (isset($_GET["approvedOnly"]) && (int)$_GET["approvedOnly"] > 0) ? 'checked="checked"' : ''; ?>/>Exibir somente os aprovados</label>
		<input type="hidden" name="eventId" value="<?php echo $eventId; ?>"/>
	</div>
</form>

<?php $dgComp->render(); ?>