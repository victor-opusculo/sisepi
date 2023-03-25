<?php
require_once("checkLogin.php");
require_once("../model/Database/libraryreservations.database.php");

$fullData = getFullReservationsList(($_GET["orderBy"] ?? ""), ($_GET["q"] ?? ""));
$fileName = "EPI-reservas-biblioteca_" . date("d-m-Y_H-i-s") . ".csv";
if (!$fullData) die("Não há dados de acordo com o critério atual de pesquisa.");

header('Content-Encoding: UTF-8');
header("Content-type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=$fileName");

$output = fopen("php://output", "w");
$header = array_keys($fullData[0]);

fwrite($output, "\xEF\xBB\xBF" . PHP_EOL);
fputcsv($output, $header, ";");

foreach($fullData as $row)
{
	fputcsv($output, $row, ";");
}

fclose($output);