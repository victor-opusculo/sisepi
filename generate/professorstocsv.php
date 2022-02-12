<?php
require_once("checkLogin.php");
require_once("../model/database/professors.database.php");

$fullData = getFullProfessors( ($_GET["orderBy"] ?? ""), ($_GET["q"] ?? "") );
$fileName = "EPI-docentes_" . date("d-m-Y_H-i-s") . ".csv";
if (!$fullData) die("Não há dados de acordo com o critério atual de pesquisa.");

header('Content-Encoding: UTF-8');
header("Content-type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=$fileName");


$output = fopen("php://output", "w");
$header = array_keys($fullData[0]);


fwrite($output, "\xEF\xBB\xBF" . PHP_EOL);
//fwrite($output, "sep=," . PHP_EOL);

fputcsv($output, $header, ";");

foreach($fullData as $row)
{
	fputcsv($output, $row, ";");
}

fclose($output);