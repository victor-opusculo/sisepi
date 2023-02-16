<?php
require_once("checkLogin.php");
require_once("../model/database/database.php");
require_once "../model/events/EventSubscription.php";

$getter = new \Model\Events\EventSubscription();
$getter->setCryptKey(getCryptoKey());

$fullData = null;
$exception = null;

$conn = createConnectionAsEditor();
try
{
	$fullData = $getter->getAllForExport($conn, $_GET['orderBy'] ?? '', $_GET['q'] ?? '');
}
catch (Exception $e)
{
	$exception = $e->getMessage();
}
finally { $conn->close(); }

if ($exception) die($exception);

$fileName = "EPI-inscricoes_" . date("d-m-Y_H-i-s") . ".csv";
if (empty($fullData)) die("Não há inscrições.");

header('Content-Encoding: UTF-8');
header("Content-type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=$fileName");

$output = fopen("php://output", "w");
$header = [ 'ID', 'Data de inscrição', 'Nome', 'E-mail' ];
$subscriptions = [];
foreach ($fullData as $sdr)
{
	$cline = $sdr->getStudentDataObject();
	$subscriptionFinalLine = [ $sdr->id, $sdr->subscriptionDate, $sdr->name, $sdr->email ];
	foreach ($cline->questions as $quest)
	{
		$headerName = trim(preg_replace("/\s+/u", " ", $quest->formInput->label));
		$isAlreadyInHeaders = array_search($headerName, $header) !== false; 
		if (!$isAlreadyInHeaders)
			$header[] = $headerName;

		$subscriptionFinalLine[array_search($headerName, $header)] = $quest->value ?? '';
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

fwrite($output, "\xEF\xBB\xBF" . PHP_EOL);

fputcsv($output, $header, ";");

foreach ($subscriptions as $si => $sv)
{
	foreach ($header as $hi => $hv)
	{
		if (!isset($sv[$hi]))
			$subscriptions[$si][$hi] = '';
	}
	ksort($subscriptions[$si]);

	fputcsv($output, $subscriptions[$si], ";");
}

fclose($output);