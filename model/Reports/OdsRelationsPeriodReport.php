<?php

namespace SisEpi\Model\Reports;

require_once __DIR__ . "/../../vendor/autoload.php";

use Exception;
use Hisune\EchartsPHP\ECharts;
use \Hisune\EchartsPHP\Config;
use \Hisune\EchartsPHP\Doc\IDE\Series;
use SisEpi\Model\Ods\OdsRelation;

final class OdsRelationsPeriodReport extends \SisEpi\Model\Reports\Report
{
    private array $odsData;
    private int $year;
    private int $htmlElementIdNumber = 0;
    private array $sections = [];

    public function __construct(int $year, \mysqli $conn)
    {
        $this->year = $year;
        $getter = new OdsRelation();
        $getter->year = $year;
        $this->odsData = $getter->getOdsData($conn);
        $rels = $getter->getAllFromYear($conn);

        if (empty($rels))
            throw new Exception("Não há dados disponíveis para o exercício selecionado.");

        $this->processData($rels);
    }

    public function getReportItemsHTML()
    {
        foreach ($this->sections as $section)
            yield $this->generateHTML($section);
    }

    private function generateHTML(array $section) : string
    {
        $section['prepareFunction']($section);

        $valuesCount = $section['valuesCount'];
        $totalCount = $section['totalCount'];

        $output = '<div class="reportItemContainer">';
        $output .= "<h3>" . hsc($section['title']) . "</h3>";
        $output .= '<div class="reportItemContainerFlex">';

        switch ($section['chartType'])
        {
            case "pie":
                $output .= $this->generateInfosHTMLforPieChart($valuesCount, $totalCount);
                $data = [];
                foreach ($valuesCount as $k => $v)
                    $data[] = [ 'name' => truncateText($k, 20), 'value' => $v['total'], 'relationsSubTotals' => $v['relationsSubTotals'] ];
                $output .= $this->generatePieChartHTML($section['title'], $data);
                break;
            case "bar":
                $output .= $this->generateInfosHTMLforBarChart($valuesCount, $totalCount);
                $chartDataObjects = array_map(fn($countItem) => [ 'value' => $countItem['total'], 'relationsSubTotals' => $countItem['relationsSubTotals'] ], array_values($valuesCount));
                $xAxisCats = array_map( fn($odsDesc) => truncateText($odsDesc, 15), array_keys($valuesCount));
                $output .= $this->generateBarChartHTML($section['title'], $xAxisCats, $chartDataObjects, count($valuesCount) > 15 ? 4 : 0);
                break;
        }
        
        $output .= "</div></div>";  



        return $output;
    }

    private function generatePieChartHTML(string $title, array $data) : string
    {
        $chart = new ECharts();
        $chart->legend->orient = 'vertical';
        $chart->legend->left = 'left';
        $chart->tooltip->trigger = 'item';
        $chart->tooltip->formatter = 'function(params)
        {
            const relationSubTotalsString = () =>
            {
                let output = "";
                for (const est of params.data.relationsSubTotals)
                    output += `${est.relationName}: ${est.value}<br/>`;
                return output;
            };
            return `<strong>${params.name}: ${params.data.value} (${params.percent}%)</strong><br/>` + relationSubTotalsString();
        }';
        $chart->setJsVar('chart' . $this->htmlElementIdNumber);
        $chart->color = self::chartColors;

        $series = new Series();
        $series->name = $title;
        $series->data = $data;
        $series->type = 'pie';
        $series->radius = '50%';

        $chart->addSeries($series);
        return '<div class="reportItemChart">' . $chart->render('chart' . $this->htmlElementIdNumber++, ['style' => 'height: 350px;']) . '</div>';
    }

    private function generateBarChartHTML(string $title, array $XAxisCategories, array $seriesData, int $xAxisInterval = 0)
    {
        $chart = new ECharts();
        $chart->xAxis = [ 'type' => 'category', 'data' => $XAxisCategories, 'axisLabel' => [ 'interval' => $xAxisInterval, 'rotate' => 30] ];
        $chart->yAxis = [ 'type' => 'value' ];
        $chart->tooltip->trigger = 'item';
        $chart->tooltip->axisPointer = [ 'type' => 'shadow' ];
        $chart->color = self::chartColors;
        $chart->tooltip->formatter = 'function(params)
        {
            const relationSubTotalsString = () =>
            {
                let output = "";
                for (const est of params.data.relationsSubTotals)
                    output += `${est.relationName}: ${est.value}<br/>`;
                return output;
            };
            return `<strong>${params.name}: ${params.data.value}</strong><br/>` + relationSubTotalsString();
        }';
        $chart->setJsVar('chart' . $this->htmlElementIdNumber);

        $series = new Series();
        $series->name = $title;
        $series->data = $seriesData;
        $series->type = 'bar';
        $series->barWidth = '40%';
        $chart->addSeries($series);

        return '<div class="reportItemChart">' . $chart->render('chart' . $this->htmlElementIdNumber++, ['style' => 'height: 350px']) . '</div>';
    }

    private function generateInfosHTMLforPieChart(array $valuesCount, int $totalCount) : string
    {
        $output = "";
        $output .= "<div class=\"reportItemInfos\">";
        foreach ($valuesCount as $val => $countsArray)
        {
            $output .= "<span class=\"reportItemInfosDataRowContainer\">";
            $output .= "<span class=\"reportItemInfosLabel hover-text\">";
            $output.= hsc(truncateText($val, 50)) . ": " . "<span class=\"tooltip-text\">" . hsc($val) . "</span>";
            $output .= "</span>";
            $output .= "<span class=\"reportItemInfosValue\">";
            $output.= hsc($countsArray['total'] . " (" . number_format(($countsArray['total'] * 100) / $totalCount, 2, ',', '') . "%)"); 
            $output .= "</span>";
            $output .= "</span>";
        }
        $output .= "<span class=\"reportItemInfosTotal\">Total: $totalCount</span>";
        $output .= "</div>";
        return $output;
    }

    private function generateInfosHTMLforBarChart(array $valuesCount, int $totalCount) : string
    {
        $output = "";
        $output .= "<div class=\"reportItemInfos\">";
        foreach ($valuesCount as $val => $countsArray)
        {
            $output .= "<span class=\"reportItemInfosDataRowContainer\">";
            $output .= "<span class=\"reportItemInfosLabel hover-text\">";
            $output.= hsc(truncateText($val, 50)) . ": " . "<span class=\"tooltip-text\">" . hsc($val) . "</span>"; 
            $output .= "</span>";
            $output .= "<span class=\"reportItemInfosValue\">";
            $output.= hsc($countsArray['total']); 
            $output .= "</span>";
            $output .= "</span>";
        }
        $output .= "<span class=\"reportItemInfosTotal\">Total: $totalCount</span>";
        $output .= "</div>";
        return $output;
    }

    private function processData(array $odsRelations)
    {
        $sections = 
        [
            [ 'title' => 'Número de eventos/relações ODS por ODS', 'chartType' => 'bar', 'ods' => [], 'prepareFunction' => [$this, 'firstSecondGraphPrepare'] ], //Number of events per ODS objective (number)
            [ 'title' => 'Número de eventos/relações ODS por ODS', 'chartType' => 'pie', 'ods' => [], 'prepareFunction' => [$this, 'firstSecondGraphPrepare'] ], //Number of events per ODS objective (number)
            [ 'title' => 'Número de eventos/relações ODS por Meta ODS', 'chartType' => 'bar', 'ods' => [], 'prepareFunction' => [$this, 'thirdFourthGraphPrepare'] ], //Number of events per ODS goal (id)
            [ 'title' => 'Número de eventos/relações ODS por Meta ODS', 'chartType' => 'pie', 'ods' => [], 'prepareFunction' => [$this, 'thirdFourthGraphPrepare'] ] //Number of events per ODS goal (id)
        ];

        foreach ($odsRelations as $relation)
        {
            $codes = $relation->codesArray;

            foreach ($sections as &$section)
            {
                foreach ($codes as $code)
                {
                    [ $number, $id ] = explode(".", $code);

                    if (!in_array($number, array_keys($section['ods'])))
                    {
                        $ods = $this->getOdsByNumber($number);
                        $section['ods'][$number] =
                        [
                            'relations' => [],
                            'description' => $ods->description ?? '(Indefinido)',
                            'goals' => 
                            [
                                $id => 
                                [
                                    'relations' => [],
                                    'description' => $this->getGoalById($ods, $id)->description ?? '(Indefinido)'
                                ]
                            ]
                        ];
                    }
                    else if (!in_array($id, array_keys($section['ods'][$number]['goals'])))
                        $section['ods'][$number]['goals'][$id] = 
                        [
                            'relations' => [],
                            'description' => $this->getGoalById($this->getOdsByNumber($number), $id)->description ?? '(Indefinido)'
                        ];

                    if (!in_array($relation, $section['ods'][$number]['relations']))
                        $section['ods'][$number]['relations'][] = $relation;

                    if (!in_array($relation, $section['ods'][$number]['goals'][$id]['relations']))
                        $section['ods'][$number]['goals'][$id]['relations'][] = $relation;
                }
            }
        }

        $this->sections = $sections;
    }

    private function firstSecondGraphPrepare(array &$section)
    {
        $section['valuesCount'] = [];
        $section['totalCount'] = 0;

        foreach ($section['ods'] as $number => $ods)
        {
            $odsFullDesc = "{$number}. {$ods['description']}";
            if (!isset($section['valuesCount'][$odsFullDesc]))
            {
                $section['valuesCount'][$odsFullDesc] = [ 'total' => 0, 'relationsSubTotals' => [] ];
            }                
            
            foreach ($ods['relations'] as $rel)
            {
                $section['valuesCount'][$odsFullDesc]['total']++;
                $section['totalCount']++;

                $section['valuesCount'][$odsFullDesc]['relationsSubTotals'][] = [ 'relationName' => $rel->name, 'value' => 1 ];
            }
        }
        ksort($section['valuesCount']);
    }

    private function thirdFourthGraphPrepare(array &$section)
    {
        $section['valuesCount'] = [];
        $section['totalCount'] = 0;

        foreach ($section['ods'] as $number => $ods)
        {
            foreach ($ods['goals'] as $id => $goal)
            {
                $goalFullDesc = "{$number}.{$id} - " . $goal['description'];
                if (!isset($section['valuesCount'][$goalFullDesc]))
                {
                    $section['valuesCount'][$goalFullDesc] = [ 'total' => 0, 'relationsSubTotals' => [] ];
                }

                foreach ($goal['relations'] as $rel)
                {

                    $section['valuesCount'][$goalFullDesc]['total']++;
                    $section['totalCount']++;

                    $section['valuesCount'][$goalFullDesc]['relationsSubTotals'][] = [ 'relationName' => $rel->name, 'value' => 1 ];
                }
            }
        }
        ksort($section['valuesCount']);
    }

    private function getOdsByNumber(int $number) : ?object
    {
        foreach ($this->odsData as $ods)
            if ($ods->number === $number)
                return $ods;
        
        return null;
    }

    private function getGoalById(object $ods, string $id) : ?object
    {
        foreach ($ods->goals as $goal)
            if ($goal->id == $id)
                return $goal;

        return null;
    }
}