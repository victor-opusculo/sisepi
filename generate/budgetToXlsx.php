<?php

require_once "../vendor/autoload.php";

use \Spatie\SimpleExcel\SimpleExcelWriter;
use \SisEpi\Model\Budget\BudgetEntry;

use \Box\Spout\Common\Entity\Style\Style;
use \Box\Spout\Common\Entity\Style\Color;
use \Box\Spout\Common\Entity\Cell;
use \Box\Spout\Common\Entity\Row;
use \Box\Spout\Common\Entity\Style\Border;
use \Box\Spout\Common\Entity\Style\BorderPart;

$year = $_GET['year'] ?? date('Y');
$searchKeywords = $_GET['q'] ?? '';
$fromDate = $_GET['fromDate'] ?? null;
$toDate = $_GET['toDate'] ?? null;
$fromValue = isset($_GET['fromValue']) && is_numeric($_GET['fromValue']) ? floatval($_GET['fromValue']) : null;
$toValue = isset($_GET['toValue']) && is_numeric($_GET['toValue']) ? floatval($_GET['toValue']) : null;

$conn = \SisEpi\Model\Database\Connection::create();
$exception = "";
$writer = null;
try
{
    $getter = new BudgetEntry();

    [ 'count' => $count, 'sumValue' => $balanceValue ] = $getter->getCount($conn, (int)$year, $searchKeywords, $fromValue, $toValue, $fromDate, $toDate);
    $allEntries = $getter->getMultiplePartially($conn, (int)$year, 1, $count, $_GET['orderBy'] ?? '', $searchKeywords, $fromValue, $toValue, $fromDate, $toDate);

    $writer = SimpleExcelWriter::streamDownload("EPI-orçamento-$year--" . date('d-m-Y_H-i-s') . ".xlsx");

    $headerStyle = (new Style)
    ->setBackgroundColor("000000")
    ->setFontBold()
    ->setFontColor("ffffff");

    $writer->noHeaderRow()
    ->addRow([$year, '', '', '', '', '', '', ''], $headerStyle)
    ->addRow([ 'ID', 'Tipo', 'Data', 'Categoria', 'Valor', 'Descrição/Detalhes', 'Evento', 'Ficha de trabalho de docente'], $headerStyle);

    $borders = new Border
    ([
        new BorderPart(Border::BOTTOM, Color::rgb(128, 128, 128), Border::WIDTH_MEDIUM, Border::STYLE_SOLID),
        new BorderPart(Border::LEFT, Color::rgb(128, 128, 128), Border::WIDTH_MEDIUM, Border::STYLE_SOLID),
        new BorderPart(Border::RIGHT, Color::rgb(128, 128, 128), Border::WIDTH_MEDIUM, Border::STYLE_SOLID),
        new BorderPart(Border::TOP, Color::rgb(128, 128, 128), Border::WIDTH_MEDIUM, Border::STYLE_SOLID),
    ]);

    foreach ($allEntries as $entry)
    {
        $valueStyle = (new Style)
        ->setFontColor($entry->value >= 0 ? '00ff00' : 'ff0000');

        $rowStyle = (new Style)
        ->setBorder($borders);

        $writer->addRow(new Row
        ([
            new Cell($entry->id),
            new Cell($entry->value >= 0 ? 'Receita' : 'Despesa'),
            new Cell(date_create($entry->date)->format('d/m/Y')),
            new Cell($entry->getOtherProperties()->categoryName),
            new Cell(abs($entry->value), $valueStyle),
            new Cell($entry->details),
            new Cell(!empty($entry->eventId) ? $entry->getOtherProperties()->eventName . " (ID: {$entry->eventId})" : ""),
            new Cell(!empty($entry->professorWorkSheetId) ? $entry->getOtherProperties()->profWorkSheetActivityName . " (ID: {$entry->professorWorkSheetId})" : ""),
        ], $rowStyle));
    }
    
    $balanceValueStyle = (new Style)
    ->setFontBold()
    ->setFontColor($balanceValue >= 0 ? '00ff00' : 'ff0000');

    $writer->addRow([]);
    $writer->addRow(new Row([new Cell("Saldo: "), new Cell($balanceValue, $balanceValueStyle)], null));

    $writer->toBrowser();
}
catch (Exception $e)
{
    $exception = $e->getMessage();
}
finally { $conn->close(); }

if ($exception)
    die($exception);