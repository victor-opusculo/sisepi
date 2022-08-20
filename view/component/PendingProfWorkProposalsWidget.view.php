<?php if ($dataRows && count($dataRows) > 0) { ?>

<table>
	<thead>
		<tr>
			<th>Proposta</th>
			<th>Docente dono</th>
			<th>Data de envio</th>
		</tr>
	</thead>
	<tbody>
        
		<?php foreach ($dataRows as $r) { ?>
		<tr>
			<td><a href="<?php echo URL\URLGenerator::generateSystemURL("professors2", "viewworkproposal", $r["id"]); ?>"><?php echo hsc($r["name"]); ?></a></td>
			<td><?php echo hsc($r["professorName"]); ?></td>
            <td style="text-align: center;"><?php echo date_format(date_create($r["registrationDate"]), "d/m/Y H:i:s"); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php } else { ?>
<div class="centControl">
	Não há planos de aula pendentes no momento.
</div>
<?php } ?>