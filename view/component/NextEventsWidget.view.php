<?php if ($dataRows && count($dataRows) > 0) { ?>

<table>
	<thead>
		<tr>
			<th>Evento</th>
			<th>Data</th>
			<th>Nome/Conteúdo</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($dataRows as $r) { ?>
		<tr>
			<td><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", $r["id"]); ?>"><?php echo hsc($r["eventName"]); ?></a></td>
			<td style="text-align: center;"><?php echo date_format(date_create($r["date"]), "d/m"); ?></td>
			<td><?php echo $r["name"]; ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php } else { ?>
<div class="centControl">
	Não há dados.
</div>
<?php } ?>