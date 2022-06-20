<?php
require_once("checkLogin.php");
require_once("../model/AnsweredEventSurvey.php");
require_once("../model/database/eventsurveys.database.php");

$fullData = getAllAnsweredSurveysOfEvent(($_GET["eventId"] ?? ""));
$fileName = "EPI-pesquisas-satisfacao_" . date("d-m-Y_H-i-s") . ".csv";
if (!$fullData) die("Não há dados disponíveis.");

$surveys = [];
$header = [ 'ID', 'Data de envio' ];
foreach ($fullData as $sdr)
{
	$cline = new AnsweredEventSurvey(json_decode($sdr['surveyJson']));
	$surveyFinalLine = [ $sdr['id'], $sdr['registrationDate'] ];
	foreach ($cline->allItemsAsObject() as $item)
	{
		$headerName = trim(preg_replace("/\s+/u", " ", $item->title));
		$isAlreadyInHeaders = array_search($headerName, $header) !== false; 
		if (!$isAlreadyInHeaders)
			$header[] = $headerName;

		$surveyFinalLine[array_search($headerName, $header)] = $item->formattedAnswer;
	}
	$surveys[] = $surveyFinalLine;
}

foreach ($surveys as $si => $sv)
{
	foreach ($header as $hi => $hv)
	{
		if (!isset($sv[$hi]))
			$surveys[$si][$hi] = '';
	}
	ksort($surveys[$si]);
}

header('Content-Encoding: UTF-8');
header("Content-type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=$fileName");

$output = fopen("php://output", "w");

fwrite($output, "\xEF\xBB\xBF" . PHP_EOL);
fputcsv($output, $header, ";");

foreach($surveys as $row)
{
	fputcsv($output, $row, ";");
}

fclose($output);