<?php

require_once("checkLogin.php");
require_once("../model/database/database.php");
require_once "../model/events/EventCertificate.php";

$getter = new \SisEpi\Model\Events\EventCertificate();
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

$fileName = "EPI-certificados_" . date("d-m-Y_H-i-s") . ".csv";
if (empty($fullData)) die("Não há certificados para o critério atual de pesquisa.");

header('Content-Encoding: UTF-8');
header("Content-type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=$fileName");

$output = fopen("php://output", "w");

fwrite($output, "\xEF\xBB\xBF" . PHP_EOL);
foreach ($fullData as $i => $cert)
{
    $current = $cert->toArrayWithPtBrHeaders();

    if ($i === 0)
        fputcsv($output, array_keys($current), ";");

    fputcsv($output, $current, ";");
}

fclose($output);