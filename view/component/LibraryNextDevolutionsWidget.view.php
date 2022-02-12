<?php 
function passedExpectedReturnDate($expDatetimeString)
{
	$dtNow = new DateTime(date("Y-m-d H:i:s"));
	$dtERD = new DateTime($expDatetimeString);
	
	return ($dtNow > $dtERD);
}

if ($dataRows && count($dataRows) > 0) { ?>

<table>
	<thead>
		<tr>
			<th>Publicação</th>
			<th>Data limite para devolução</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($dataRows as $r) { ?>
		<tr>
			<td><a href="<?php echo URL\URLGenerator::generateSystemURL("libraryborrowedpubs", "view", $r["id"]); ?>"><?php echo hsc($r["title"]); ?></a></td>
			<td style="text-align: center; <?php echo passedExpectedReturnDate($r["expectedReturnDatetime"]) ? 'color: red;' : ''; ?>"><?php echo date_format(date_create($r["expectedReturnDatetime"]), "d/m H:i"); ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>

<?php } else { ?>
<div class="centControl">
	Não há empréstimos a serem finalizados no futuro próximo.
</div>
<?php } ?>