<?php

use SisEpi\Model\Database\Connection;
use SisEpi\Model\Ods\OdsRelation;

require_once("checkLogin.php");
require_once "../vendor/autoload.php";

$conn = Connection::create();

$getter = new OdsRelation();
$count = $getter->getCount($conn, $_GET['q'] ?? '');
$fullData = $getter->getMultiplePartially($conn, 1, $count, $_GET['orderBy'] ?? '', $_GET['q'] ?? '');

$conn->close();
$fileName = "EPI-relações-ODS_" . date("d-m-Y_H-i-s") . ".csv";
if (empty($fullData)) die("Não há dados de acordo com o critério atual de pesquisa.");

header('Content-Encoding: UTF-8');
header("Content-type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=$fileName");

$output = fopen("php://output", "w");
$header = [ "ID", "Nome", "Exercício", "Metas ODS", "Evento relacionado" ];

fwrite($output, "\xEF\xBB\xBF" . PHP_EOL);
fputcsv($output, $header, ";");

foreach($fullData as $rel)
{
	fputcsv($output, 
	[
		$rel->id,
		$rel->name,
		$rel->year,
		implode(" / ", json_decode($rel->odsCodes)),
		isset($rel->eventId) ? $rel->getOtherProperties()->eventName . " (ID: {$rel->eventId})" : "Nenhum"
	], ";");
}

fclose($output);