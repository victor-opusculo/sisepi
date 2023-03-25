<?php
namespace SisEpi\Model\Reports;

require_once __DIR__ . '/../../includes/common.php';
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/Data/namespace.php';

use DateTime;
use Exception;
use \SisEpi\Model\Calendar\CalendarDate;
use \SisEpi\Model\Events\EventDate;
use \Spatie\SimpleExcel\SimpleExcelWriter;
use \Box\Spout\Common\Entity\Style\Style;
use \Box\Spout\Common\Entity\Style\Color;
use \Box\Spout\Common\Entity\Cell;
use \Box\Spout\Common\Entity\Row;
use \Box\Spout\Common\Entity\Style\Border;
use \Box\Spout\Common\Entity\Style\BorderPart;


final class CalendarPeriodReport extends Report
{
    public function __construct(\mysqli $conn, string $fromDate, string $toDate, bool $toBrowser = true)
    {
        $from = new DateTime($fromDate);
        $to = new DateTime($toDate);

        if ($from > $to)
            throw new Exception('O início do período está numa data superior ao término.');

        $diff = $from->diff($to);
        if ($diff->y >= 2 && $toBrowser)
            throw new Exception('O período limite para visualização no navegador é de dois anos. Você pode visualizar exportando para XLSX.');
        
        $eventDatesGetter = new EventDate();
        $gotEventDates = $eventDatesGetter->getAllFromPeriod($conn, $fromDate, $toDate);
        array_walk($gotEventDates, fn($ed) => $ed->fetchTraits($conn));

        $calendarDatesGetter = new CalendarDate();
        $gotCalendarDates = $calendarDatesGetter->getAllFromPeriod($conn, $fromDate, $toDate);
        array_walk($gotCalendarDates, fn($cd) => $cd->fetchTraits($conn));

        $eventDatesTransformRules =
        [
            'type' => fn($ed) => 'event',
            'date' => fn($ed) => $ed->date,
            'beginTime' => fn($ed) => $ed->beginTime,
            'endTime' => fn($ed) => $ed->endTime,
            'title' => fn($ed) => $ed->getOtherProperties()->eventName,
            'description' => fn($ed) => $ed->name,
            'local' => fn($ed) => $ed->getOtherProperties()->locationName,
            'mode' => fn($ed) => $ed->getOtherProperties()->locationType === 'physical' ? 'Presencial' : ($ed->getOtherProperties()->locationType === 'online' ? 'Remoto' : 'Indefinido'),
            'traits' => fn($ed) => $ed->traits,
            'style' => fn($ed) => json_decode($ed->getOtherProperties()->calendarInfoBoxStyleJson ?? '')
        ];

        $calendarDatesTransformRules =
        [
            'type' => fn($cd) => $cd->type,
            'date' => fn($cd) => $cd->date,
            'beginTime' => fn($cd) => $cd->beginTime,
            'endTime' => fn($cd) => $cd->endTime,
            'title' => fn($cd) => $cd->title,
            'description' => fn($cd) => $cd->description,
            'local' => fn($cd) => '',
            'mode' => fn($cd) => '',
            'traits' => fn($cd) => $cd->traits,
            'style' => fn($cd) => json_decode($cd->styleJson ?? '')
        ];

        $this->allDatesList = [ ...\Data\transformDataRows($gotEventDates, $eventDatesTransformRules), ...\Data\transformDataRows($gotCalendarDates, $calendarDatesTransformRules)  ];
        usort($this->allDatesList, CalendarDate::class . '::calendarCompareDateTimeFromEventsList');

        if (count($this->allDatesList) < 1)
            throw new Exception("Nenhum apontamento ou evento para o período selecionado.");
    }

    private array $allDatesList; 

    private function generateStyleHtml($item) : string
    {
        $classContent = "";

        switch($item['type'])
        {
            case 'holiday': $classContent .= 'holiday'; break;
            case 'event': $classContent .= 'event'; break;
            case 'publicsimpleevent':
            case 'privatesimpleevent': $classContent .= 'simpleevent'; break;
            default: break;
        }

        $styleStruct = $item['style'] ?? null;
        if (!empty($styleStruct))
            return " style=\" color:{$styleStruct->textColor}; background-color:{$styleStruct->backgroundColor}; \" ";
        
        if (!empty($classContent))
            return " class=\"$classContent\" ";

        return '';
    } 

    private function generateStyleXlsx($item, Border $border) : Style
    {
        $style = new Style();

        $styleStruct = $item['style'] ?? null;
        if (empty($styleStruct))
        {
            switch($item['type'])
            {
                case 'holiday':
                    $style->setBackgroundColor('ffc0cb');
                    $style->setFontColor('cc0000'); 
                    break;
                case 'event':
                    $style->setBackgroundColor('d3d3d3');
                    $style->setFontColor('888888');
                    break;
                case 'publicsimpleevent':
                case 'privatesimpleevent': 
                    $style->setBackgroundColor('ffffe0');
                    $style->setFontColor('aaaa00');
                    break;
                default: break;
            }
        }
        else
        {
            $style->setBackgroundColor(substr($styleStruct->backgroundColor ?? '#ffffff', 1));
            $style->setFontColor(substr($styleStruct->textColor ?? '000000', 1));
        }

        $style->setBorder($border);

        return $style;
    }

    public function getReportItemsHTML()
    {
        yield '<table class="visibleBordersTable">';
        yield 
        '<thead>
            <tr>
                <th>Data</th>
                <th>Horário</th>
                <th>Evento</th>
                <th>Descrição/Detalhes</th>
                <th>Local</th>
                <th>Modalidade</th>
                <th>Traços</th>
            </tr>
        </thead>';

        yield '<tbody>';

        foreach ($this->allDatesList as $item)
        {
            $out = "<tr " . $this->generateStyleHtml($item) . ">";
            $out .= "<td>" . hsc(date_create($item['date'])->format('d/m/Y')) . "</td>";
            $out .= "<td>" . hsc($item['beginTime'] . '-' . $item['endTime']) . "</td>";
            $out .= "<td>" . hsc($item['title']) . "</td>";
            $out .= "<td>" . hsc($item['description']) . "</td>";
            $out .= "<td>" . hsc($item['local']) . "</td>";
            $out .= "<td>" . hsc($item['mode']) . "</td>";
            $out .= "<td>" . (function($item)
            {
                $imgs = "";
                foreach ($item['traits'] as $trait)
                {
                    $imgs .= '<img src="' . \URL\URLGenerator::generateFileURL("uploads/traits/{$trait->id}.{$trait->fileExtension}") . '" height="50" 
                    title="' . hscq($trait->name . ': ' . $trait->description) . '" />';
                }
                return $imgs;
            })($item) . "</td>";
            $out .= "</tr>";

            yield $out;
        }

        yield '</tbody>';
        yield '</table>';
    }

    public function writeXlsx(SimpleExcelWriter $writer, $flusherFunction = null)
    {
        $headerStyle = new Style();
        $headerStyle->setBackgroundColor("000000");
        $headerStyle->setFontBold();
        $headerStyle->setFontColor("ffffff");

        $writer->noHeaderRow();

        $writer->addRow([ 'Data', 'Horário de Início', 'Horário de Término', 'Evento', 'Descrição/Detalhes', 'Local', 'Modalidade', 'Traços' ], $headerStyle);

        $borders = new Border
        ([
            new BorderPart(Border::BOTTOM, Color::rgb(128, 128, 128), Border::WIDTH_MEDIUM, Border::STYLE_SOLID),
            new BorderPart(Border::LEFT, Color::rgb(128, 128, 128), Border::WIDTH_MEDIUM, Border::STYLE_SOLID),
            new BorderPart(Border::RIGHT, Color::rgb(128, 128, 128), Border::WIDTH_MEDIUM, Border::STYLE_SOLID),
            new BorderPart(Border::TOP, Color::rgb(128, 128, 128), Border::WIDTH_MEDIUM, Border::STYLE_SOLID),
        ]);

        foreach ($this->allDatesList as $i => $item)
        {
            $itemStyle = $this->generateStyleXlsx($item, $borders);

            $row = new Row
            ([
                new Cell(date_create($item['date'])->format('d/m/Y')),
                new Cell($item['beginTime']),
                new Cell($item['endTime']),
                new Cell($item['title']),
                new Cell($item['description']),
                new Cell($item['local']),
                new Cell($item['mode']),
                new Cell(array_reduce($item['traits'], fn($carry, $t) => $carry . $t->name . ", ", "")),
            ], $itemStyle);

            $writer->addRow($row);

            if (isset($flusherFunction) && $i % 1000 === 0)
                $flusherFunction();
        }

        return $writer;
    }
}