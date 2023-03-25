<?php
require_once("checkLogin.php");
require_once("../model/Database/librarycollection.database.php");
require_once "../vendor/autoload.php";

$conn = createConnectionAsEditor();
$fullData = (new \SisEpi\Model\LibraryCollection\Publication)->getAllForExport($conn, $_GET['orderBy'] ?? '', $_GET['q'] ?? '');
$conn->close();
$fileName = "EPI-acervo-biblioteca_" . date("d-m-Y_H-i-s") . ".csv";
if (empty($fullData)) die("Não há dados de acordo com o critério atual de pesquisa.");

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