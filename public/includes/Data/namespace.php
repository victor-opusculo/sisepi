<?php

namespace Data;

function transformDataRows($input, $rules)
{
	$output = [];
	
	if ($input)
		foreach ($input as $row)
		{
			$newRow = [];
			foreach ($rules as $newKeyName => $ruleFunction)
				$newRow[$newKeyName] = $ruleFunction($row);

			$output[] = $newRow;
		}
		
	return $output;
}