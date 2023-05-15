<?php
if (isset($dataRows[0]))
{
	if (!function_exists('formatCellContent'))
	{
		function formatCellContent($value)
		{
			if ($value instanceof DataGridCellValue)
				return $value->generateHTML();
			else
				return nl2br(hsc($value));
		}
	}

	if (!function_exists('applyCustomButtonsParameters'))
	{
		function applyCustomButtonsParameters($buttonUrl, $parametersNamesArray, $currentDataRow)
		{
			$finalUrl = $buttonUrl;
			foreach ($parametersNamesArray as $name => $columnNameOrFixed)
				$finalUrl = str_replace('{' . $name . '}', (($columnNameOrFixed instanceof FixedParameter) ? (string)$columnNameOrFixed : $currentDataRow[$columnNameOrFixed]), $finalUrl);
			return $finalUrl;
		}
	}
?>
<table>
	<thead>
		<tr>
			<?php
			$count = 0;
			foreach ($dataRows[0] as $column => $value)
			{
				$count++;
				//ignore columns defined in $columnsToHide
				if (in_array($column, $columnsToHide) === false)
				{
					//print column name
					echo "<th>" . $column . "</th>";
				}
				
				//create details, edit, delete and select links if needed
				if ($count === count($dataRows[0]))
				{
					if (isset($detailsButtonURL) && !isset($columnNameAsDetailsButton)) echo '<th class="shrinkCell">Detalhes</th>';
					if (isset($editButtonURL)) echo '<th class="shrinkCell">Editar</th>';
					if (isset($deleteButtonURL)) echo '<th class="shrinkCell">Excluir</th>';
					if (isset($selectButtonOnClick)) echo '<th class="shrinkCell">Selecionar</th>';
					if (count($customButtons) > 0)
						foreach ($customButtons as $label => $link)
							echo '<th class="shrinkCell">' . $label . '</th>';
				}
			}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($dataRows as $row)
		{
			echo "<tr>";
			$count = 0;
			foreach ($row as $column => $value)
			{
				$count++;
				//ignore columns defined in $columnsToHide
				if (in_array($column, $columnsToHide) === false)
				{
					//print row value
					if (isset($columnNameAsDetailsButton) && $columnNameAsDetailsButton === $column)
					{
						echo '<td><a href="' . str_replace("{param}", $row[$RudButtonsFunctionParamName], $detailsButtonURL) . '">' . formatCellContent($value) . '</a></td>';
					}
					else
					{
						echo "<td>" . formatCellContent($value) . "</td>";
					}
				}
				
				//create details, edit and delete links if needed
				if ($count === count($row))
				{
					if (isset($detailsButtonURL) && !isset($columnNameAsDetailsButton)) echo '<td class="shrinkCell"><a href="' . str_replace("{param}", $row[$RudButtonsFunctionParamName], $detailsButtonURL) .  '">Detalhes</a></td>';
					if (isset($editButtonURL)) echo '<td class="shrinkCell"><a href="' . str_replace("{param}", $row[$RudButtonsFunctionParamName], $editButtonURL) . '">Editar</a></td>';
					if (isset($deleteButtonURL)) echo '<td class="shrinkCell"><a href="' . str_replace("{param}", $row[$RudButtonsFunctionParamName], $deleteButtonURL) . '">Excluir</a></td>';
					if (isset($selectButtonOnClick)) echo '<td class="shrinkCell"><a href="#" onclick="' . str_replace("{param}", $row[$RudButtonsFunctionParamName], $selectButtonOnClick) . '">Selecionar</a></td>';
				
					if (count($customButtons) > 0)
						foreach ($customButtons as $label => $link)
							echo '<td class="shrinkCell"><a href="' . applyCustomButtonsParameters($link, $customButtonsParameters, $row) . '">' . $label . '</a></td>';
				}
			}
			echo "</tr>";
		}
		?>
	</tbody>
</table>
<?php
}
else
	echo "Não há dados disponíveis.";
?>