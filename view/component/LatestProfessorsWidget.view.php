<?php if ($dataRows && count($dataRows) > 0) { ?>
<table>
	<thead>
		<tr>
			<th>Nome</th>
			<th>Data de cadastro</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($dataRows as $r) { ?>
		<tr>
			<td><a href="<?php echo URL\URLGenerator::generateSystemURL("professors", "view", $r["id"]); ?>"><?php echo hsc($r["name"]); ?></a></td>
			<td style="text-align: center;"><?php echo date_format(date_create($r["registrationDate"]), "d/m/Y H:i:s"); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php } else { ?>
<div class="centControl">
	Não há dados.
</div>
<?php } ?>