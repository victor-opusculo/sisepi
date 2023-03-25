<?php

require_once "../vendor/autoload.php";

use \Spatie\SimpleExcel\SimpleExcelWriter;
use \SisEpi\Model\Reports\CalendarPeriodReport;

$from = $_GET['begin'] ?? '';
$to = $_GET['end'] ?? '';

$conn = \SisEpi\Model\Database\Connection::create();
$exception = "";
$writer = null;
try
{
    if (empty($from) || empty($to))
        throw new Exception('Nenhum período especificado.');
    
    $report = new CalendarPeriodReport($conn, $from, $to);
    $writer = SimpleExcelWriter::streamDownload("EPI-agenda-$from-a-$to.xlsx");
    $report->writeXlsx($writer, fn() => flush() );
    $writer->toBrowser();
}
catch (Exception $e)
{
    $exception = $e->getMessage();
}
finally { $conn->close(); }

if ($exception)
    die($exception);