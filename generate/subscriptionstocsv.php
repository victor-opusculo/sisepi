<?php
require_once("checkLogin.php");
require_once("../model/database/students.database.php");

$fullData = getAllSubscriptions( ($_GET["eventId"] ?? "") );
$fileName = "EPI-inscricoes_" . date("d-m-Y_H-i-s") . ".csv";
if (!$fullData) die("Não há inscrições.");


header('Content-Encoding: UTF-8');
header("Content-type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=$fileName");


$output = fopen("php://output", "w");
$header = [ 'ID', 'Data de inscrição', 'Nome', 'E-mail' ];
$subscriptions = [];
foreach ($fullData as $sdr)
{
	$cline = json_decode($sdr['subscriptionDataJson']);
	$subscriptionFinalLine = [ $sdr['id'], $sdr['subscriptionDate'], $sdr['name'], $sdr['email'] ];
	foreach ($cline->questions as $quest)
	{
		$headerName = trim(preg_replace("/\s+/u", " ", $quest->formInput->label));
		$isAlreadyInHeaders = array_search($headerName, $header) !== false; 
		if (!$isAlreadyInHeaders)
			$header[] = $headerName;

		$subscriptionFinalLine[array_search($headerName, $header)] = $quest->value;
	}

	foreach ($cline->terms as $term)
	{
		$headerName = trim(preg_replace("/\s+/u", " ", $term->name));
		$isAlreadyInHeaders = array_search($headerName, $header) !== false; 
		if (!$isAlreadyInHeaders)
			$header[] = $headerName;

		$subscriptionFinalLine[array_search($headerName, $header)] = $term->value == 1 ? 'Concorda' : 'Não concorda';
	}
	$subscriptions[] = $subscriptionFinalLine;
}

foreach ($subscriptions as $si => $sv)
{
	foreach ($header as $hi => $hv)
	{
		if (!isset($sv[$hi]))
			$subscriptions[$si][$hi] = '';
	}
	ksort($subscriptions[$si]);
}

fwrite($output, "\xEF\xBB\xBF" . PHP_EOL);
//fwrite($output, "sep=," . PHP_EOL);

fputcsv($output, $header, ";");

foreach($subscriptions as $row)
{
	fputcsv($output, $row, ";");
}

fclose($output);