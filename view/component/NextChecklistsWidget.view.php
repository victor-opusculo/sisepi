<?php if ($nextChecklistsDataRows) { ?>

<?php 

$verifyForUncheckedItems = function($drs, $blockToRead)
{
	$filtered = [];
	foreach ($drs as $dr)
	{
		$checklistObj = json_decode($dr['checklistJson']);
		$itemsToBeChecked = 0;
		foreach($checklistObj->$blockToRead as $item)
		{
			if (!empty($item->responsibleUser) && (int)$item->responsibleUser === (int)$_SESSION['userid'])
			{
				if ((empty($item->value) || $item->value === '0' || $item->value === '') && empty($item->checkList))
					$itemsToBeChecked++;

				if (!empty($item->checkList))
					foreach ($item->checkList as $subItem)
						if (empty($subItem->value) || $subItem->value === '0' || $subItem->value === '')
							$itemsToBeChecked++;
			}
				
			if (!empty($item->checkList) && empty($item->responsibleUser))
				foreach ($item->checkList as $subItem)
				{
					if (!empty($subItem->responsibleUser) && (int)$subItem->responsibleUser === (int)$_SESSION['userid'])
						if (empty($subItem->value) || $subItem->value === '0' || $subItem->value === '')
							$itemsToBeChecked++;
				}
		}
		if ($itemsToBeChecked > 0)
		{
			$dr['itemsToBeChecked'] = $itemsToBeChecked;
			$filtered[] = $dr;
		}
	}
	return $filtered;
};

$preeventFiltered = !empty($nextChecklistsDataRows['preevent']) ? $verifyForUncheckedItems($nextChecklistsDataRows['preevent'], 'preevent') : [];
$eventdateFiltered = !empty($nextChecklistsDataRows['eventdate']) ? $verifyForUncheckedItems($nextChecklistsDataRows['eventdate'], 'eventdate') : [];
$posteventFiltered = !empty($nextChecklistsDataRows['postevent']) ? $verifyForUncheckedItems($nextChecklistsDataRows['postevent'], 'postevent') : [];
?>
<h4>Pré-evento</h4>
<?php if (count($preeventFiltered) > 0): ?>
<table>
	<thead>
		<tr>
			<th>Evento</th>
			<th>Início</th>
			<th>Pendências</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($preeventFiltered as $r) { ?>
		<tr>
			<td><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", $r["id"]); ?>"><?php echo hsc($r["name"]); ?></a></td>
			<td class="centControl"><?php echo date_format(date_create($r["beginDate"]), "d/m/Y"); ?></td>
			<td class="centControl"><?php echo $r["itemsToBeChecked"] . ' item(ns) pendente(s)'; ?></td>
			<td class="shrinkCell"><a href="<?php echo URL\URLGenerator::generateSystemURL("eventchecklists", "view", $r['checklistId']); ?>">Abrir checklist</a></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php else: ?>
	<p class="centControl">Você não tem pendências em checklists de pré-evento no futuro próximo.</p>
<?php endif; ?>

<h4>Datas de evento</h4>
<?php if (count($eventdateFiltered) > 0): ?>
<table>
	<thead>
		<tr>
			<th>Evento</th>
			<th>Conteúdo</th>
			<th>Data</th>
			<th>Pendências</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($eventdateFiltered as $r) { ?>
		<tr>
			<td><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", $r["id"]); ?>"><?php echo hsc($r["name"]); ?></a></td>
			<td><?php echo $r['eventDateName']; ?></td>
			<td class="centControl"><?php echo date_format(date_create($r["beginDate"]), "d/m/Y"); ?></td>
			<td class="centControl"><?php echo $r["itemsToBeChecked"] . ' item(ns) pendente(s)'; ?></td>
			<td class="shrinkCell"><a href="<?php echo URL\URLGenerator::generateSystemURL("eventchecklists", "view", $r['checklistId']); ?>">Abrir checklist</a></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php else: ?>
	<p class="centControl">Você não tem pendências em checklists de datas de eventos no momento.</p>
<?php endif; ?>

<h4>Pós-evento</h4>
<?php if (count($posteventFiltered) > 0): ?>
<table>
	<thead>
		<tr>
			<th>Evento</th>
			<th>Término</th>
			<th>Pendências</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($posteventFiltered as $r) { ?>
		<tr>
			<td><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", $r["id"]); ?>"><?php echo hsc($r["name"]); ?></a></td>
			<td class="centControl"><?php echo date_format(date_create($r["endDate"]), "d/m/Y"); ?></td>
			<td class="centControl"><?php echo $r["itemsToBeChecked"] . ' item(ns) pendente(s)'; ?></td>
			<td class="shrinkCell"><a href="<?php echo URL\URLGenerator::generateSystemURL("eventchecklists", "view", $r['checklistId']); ?>">Abrir checklist</a></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php else: ?>
	<p class="centControl">Você não tem pendências em checklists de pós-evento.</p>
<?php endif; ?>

<?php } else { ?>
<div class="centControl">
	Não há dados.
</div>
<?php } ?>