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

function formatCPF($cpfInput) : string
{
    $cpf = (string)$cpfInput;
    $cpf = preg_replace('/\D+/', '', $cpf);
    $cpf = substr($cpf, 0, 11);
    $cpf = str_pad($cpf, 11, '*');
    
    return preg_replace('/([\d\*]{3})([\d\*]{3})([\d\*]{3})([\d\*]{2})/', '$1.$2.$3-$4' , $cpf);
}

function formatCNPJ($cnpjInput) : string
{
    $cnpj = (string)$cnpjInput;
    $cnpj = preg_replace('/\D+/', '', $cnpj);
    $cnpj = substr($cnpj, 0, 14);
    $cnpj = str_pad($cnpj, 14, '*');
    
    return preg_replace('/([\d\*]{2})([\d\*]{3})([\d\*]{3})([\d\*]{4})([\d\*]{2})/', '$1.$2.$3/$4-$5' , $cnpj);
}



function getSubscriptionInfoFromDataObject(object $dataObject, string $identifier)
{
	$infoQuests = array_filter($dataObject->questions, fn($q) => $q->identifier === $identifier);
	return array_pop($infoQuests)->value ?? null;
}

function isDbValueDateTime(string $value) : bool
{
	$date = preg_match('/^\d\d\d\d-\d\d-\d\d$/', $value);
	$datetime = preg_match('/^\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d$/', $value);

	return ($date !== 0 && $date !== null) || ($datetime !== 0 && $datetime !== null);
}