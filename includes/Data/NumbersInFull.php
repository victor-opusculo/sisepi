<?php

namespace Data\NumbersInFull;

function moneyValueInFull($value) : string
{
	if ($value >= 1000000) return ''; 
	
	$formattedValue = number_format($value, 2, ',', '.');
	$intAndCents = explode(',', $formattedValue);
	$intPart = $intAndCents[0];
	$centsPart = $intAndCents[1];
	
	$intPartThousands = explode('.', $intPart);
	$intPartThousands = array_reverse($intPartThousands);
	$intPartThousands = array_map( function($thp)
	{
		return str_pad($thp, 3, "0", STR_PAD_LEFT);
	}, $intPartThousands);
	
	$output = "";
	
	if ($value == 0) return 'zero real';
	
	if (isset($intPartThousands[1])) 
		$output .= getThousand($intPartThousands[1]) . ((int)$intPartThousands[0] <= 100 && (int)$intPartThousands[0] > 0 ? 'e ' : '');
	if (isset($intPartThousands[0]))
		$output .= getHundred($intPartThousands[0]);
	
	$output .= (int)$intPart > 1 ? 'reais ': 'real ';
	
	if((int)$centsPart === 1)
		$output .= 'e um centavo';
	else if ((int)$centsPart > 1)
		$output .= 'e ' . getTen($centsPart) . 'centavos';
	
	return trim($output);
}

function integerNumberInFull($value)
{
	if ($value >= 1000000) return ''; 
	
	$formattedValue = number_format($value, 0, ',', '.');
	
	$intPartThousands = explode('.', $formattedValue);
	$intPartThousands = array_reverse($intPartThousands);
	$intPartThousands = array_map( function($thp)
	{
		return str_pad($thp, 3, "0", STR_PAD_LEFT);
	}, $intPartThousands);
	
	$output = "";
	
	if ((int)$value === 0) return 'zero';
	
	if (isset($intPartThousands[1])) 
		$output .= getThousand($intPartThousands[1]) . ((int)$intPartThousands[0] <= 100 && (int)$intPartThousands[0] > 0 ? 'e ' : '');
	if (isset($intPartThousands[0]))
		$output .= getHundred($intPartThousands[0]);
		
	return $output;
}

function getHundred($hundredPart)
{
	$output = "";
	switch($hundredPart[0])
	{
		case '0': break;
		case '1': $output .= ($hundredPart[1] !== '0' || $hundredPart[2] !== '0') ? 'cento ' : 'cem '; break;
		case '2': $output .= 'duzentos '; break;
		case '3': $output .= 'trezentos '; break;
		case '4': $output .= 'quatrocentos '; break;
		case '5': $output .= 'quinhentos '; break;
		case '6': $output .= 'seiscentos '; break;
		case '7': $output .= 'setecentos '; break;
		case '8': $output .= 'oitocentos '; break;
		case '9': $output .= 'novecentos '; break;
	}
	
	$output .= ($output && ($hundredPart[1] !== '0' || $hundredPart[2] !== '0') ? 'e ' : '') . getTen($hundredPart[1] . $hundredPart[2]);
	
	return $output;
}

function getThousand($thousandPart)
{
	$output = "";
	$output .= getHundred($thousandPart);
	
	$output .= "mil ";
	return $output;
}

function getTen($tenPart)
{
	$output = "";
	switch ($tenPart[0])
	{
		case '0': break;
		case '1':
			switch ($tenPart[1])
			{
				case '0': $output .= 'dez '; break;
				case '1': $output .= 'onze '; break;
				case '2': $output .= 'doze '; break;
				case '3': $output .= 'treze '; break;
				case '4': $output .= 'quatorze '; break;
				case '5': $output .= 'quinze '; break;
				case '6': $output .= 'dezesseis '; break;
				case '7': $output .= 'dezessete '; break;
				case '8': $output .= 'dezoito '; break;
				case '9': $output .= 'dezenove '; break;
			}
			break;
		case '2': $output .= 'vinte '; break;
		case '3': $output .= 'trinta '; break;
		case '4': $output .= 'quarenta '; break;
		case '5': $output .= 'cinquenta '; break;
		case '6': $output .= 'sessenta '; break;
		case '7': $output .= 'setenta '; break;
		case '8': $output .= 'oitenta '; break;
		case '9': $output .= 'noventa '; break;
	}
	
	if ((int)$tenPart[0] !== 1 && (int)$tenPart[1] > 0)
		$output .= ($output ? 'e ' : '') . getUnit($tenPart[1]);
	
	return $output;
}

function getUnit($unitPart)
{
	$output = "";
	switch($unitPart)
	{
		case '0': break;
		case '1': $output .= 'um '; break;
		case '2': $output .= 'dois '; break;
		case '3': $output .= 'três '; break;
		case '4': $output .= 'quatro '; break;
		case '5': $output .= 'cinco '; break;
		case '6': $output .= 'seis '; break;
		case '7': $output .= 'sete '; break;
		case '8': $output .= 'oito '; break;
		case '9': $output .= 'nove '; break;
	}
	
	return $output;
}