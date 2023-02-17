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

function getEventMode(string $locationTypes) : string
{
	$datesModes = explode(",", $locationTypes);

	$physicalModes = array_filter($datesModes, fn($m) => $m === "physical");
	$onlineModes = array_filter($datesModes, fn($m) => $m === "online");
	$nullModes = array_filter($datesModes, fn($m) => $m === "null");

	if (count($physicalModes) === count($datesModes))
		return "Presencial";
	else if (count($onlineModes) === count($datesModes))
		return "Remoto";
	else if (count($nullModes) === count($datesModes))
		return "Indefinido";
	else
		return "Misto";
}

function getSubscriptionInfoFromDataObject(object $dataObject, string $identifier)
{
	$infoQuests = array_filter($dataObject->questions, fn($q) => $q->identifier === $identifier);
	return array_pop($infoQuests)->value ?? null;
}

if (!function_exists('Data\formatPersonNameCase'))
{
	function formatPersonNameCase($fullName)
	{
		return mb_convert_case($fullName, MB_CASE_TITLE, "UTF-8");
	}
}