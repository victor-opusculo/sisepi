<?php
require_once __DIR__ . "/Report.php";
require_once __DIR__ . "/../../includes/common.php";
require_once __DIR__ . "/../database/reports.database.php";
require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../events/EventSubscription.php";

use Hisune\EchartsPHP\ECharts;
use \Hisune\EchartsPHP\Config;
use \Hisune\EchartsPHP\Doc\IDE\Series;

final class EventSubscriptionReport extends Report
{
    public function __construct(array $eventIds, mysqli $conn)
    {
        $getter = new \Model\Events\EventSubscription();
        $getter->setCryptKey(getCryptoKey());
        foreach ($eventIds as $eventId)
        {
            $getter->eventId = $eventId;
            $subsFromEvent = $getter->getMultipleFromEvent($conn);
            $this->subscriptionObjects = [...$this->subscriptionObjects, ...$subsFromEvent];
        }

        if (empty($this->subscriptionObjects))
            throw new Exception("Não há dados disponíveis para os eventos selecionados.");

        $this->processData();
    }

    private array $subscriptionObjects = [];
    private ?array $outputData;
    private int $htmlElementIdNumber = 0;

    public function getReportItemsHTML()
    {
        yield $this->generateHeadInfosHTML();

        foreach ($this->outputData as $item)
        {
            yield $this->generateHTML($item);
        }
    }

    private function generateHeadInfosHTML()
    {
        $output = '<div class="reportItemContainer">';
        $output .= "<h3>Geral</h3>";
        $output .= '<div class="reportItemContainerFlex">';

        $subscriptionNumber = count($this->subscriptionObjects);
        $emails = [];
        $names = [];
        foreach ($this->subscriptionObjects as $sub)
        {
            if (array_search($sub->email, $emails) === false)
                $emails[] = $sub->email;
            
            if (array_search($sub->name, $names) === false)
                $names[] = $sub->name;
        }
        $uniqueSubscriptions = count($emails);

        $output .= "<div class=\"reportItemInfos\"><ul>";
            $output .= "<li>Número de inscrições: {$subscriptionNumber}</li>";
            $output .= "<li>Número de participantes (únicos): {$uniqueSubscriptions}</li>";
        $output .= "</ul></div>";

        $output .= "</div></div>";

        $output .= '<div class="reportItemContainer">';
        $output .= "<h3>Nomes dos participantes</h3>";
        $output .= '<div class="reportItemContainerFlex">';
        $output .= "<div class=\"reportItemInfos\">";
            $output .= "<ol>";
            foreach ($names as $name)
                $output .= "<li>" . hsc($name) . "</li>";
            $output .= "</ol>";
        $output .= "</div>";
        $output .= "</div></div>";


        return $output;
    }

    private function generatePieChartHTML(object $question, array $data) : string
    {
        $chart = new ECharts();
        $chart->legend->orient = 'vertical';
        $chart->legend->left = 'left';
        $chart->tooltip->trigger = 'item';
        $chart->tooltip->formatter = 'function(params)
        {
            const eventsSubTotalsString = () =>
            {
                let output = "";
                for (const est of params.data.eventsSubTotals)
                    output += `${est.eventName}: ${est.value}<br/>`;
                return output;
            };
            return `<strong>${params.name}: ${params.data.value} (${params.percent}%)</strong><br/>` + eventsSubTotalsString();
        }';
        $chart->setJsVar('chart' . $this->htmlElementIdNumber);
        $chart->color = self::chartColors;

        $series = new Series();
        $series->name = $question->title;
        $series->data = $data;
        $series->type = 'pie';
        $series->radius = '50%';

        $chart->addSeries($series);
        return '<div class="reportItemChart">' . $chart->render('chart' . $this->htmlElementIdNumber++, ['style' => 'height: 350px;']) . '</div>';
    }

    private function generateBarChartHTML(object $question, array $XAxisCategories, array $seriesData)
    {
        $chart = new ECharts();
        $chart->xAxis = [ 'type' => 'category', 'data' => $XAxisCategories, 'axisLabel' => [ 'interval' => 0, 'rotate' => 30] ];
        $chart->yAxis = [ 'type' => 'value' ];
        $chart->tooltip->trigger = 'item';
        $chart->tooltip->axisPointer = [ 'type' => 'shadow' ];
        $chart->color = self::chartColors;
        $chart->tooltip->formatter = 'function(params)
        {
            const eventsSubTotalsString = () =>
            {
                let output = "";
                for (const est of params.data.eventsSubTotals)
                    output += `${est.eventName}: ${est.value}<br/>`;
                return output;
            };
            return `<strong>${params.name}: ${params.data.value}</strong><br/>` + eventsSubTotalsString();
        }';
        $chart->setJsVar('chart' . $this->htmlElementIdNumber);

        $series = new Series();
        $series->name = $question->title;
        $series->data = $seriesData;
        $series->type = 'bar';
        $series->barWidth = '40%';
        $chart->addSeries($series);

        return '<div class="reportItemChart">' . $chart->render('chart' . $this->htmlElementIdNumber++, ['style' => 'height: 350px']) . '</div>';
    }

    private function generateMapChartHTML(object $question, array $data, int $maxValue) : string
    {
        $chart = new ECharts();
        $chart->visualMap->min = 0;
        $chart->visualMap->max = $maxValue;
        $chart->visualMap->text = ['Mais', 'Menos'];
        $chart->visualMap->calculable = true;
        $chart->visualMap->inRange['color'] = ['#49d874', '#114220'];
        $chart->tooltip->trigger = 'item';
        $chart->tooltip->formatter = 'function(params)
        {
            const eventsSubTotalsString = () =>
            {
                let output = "";
                for (const est of params.data.eventsSubTotals)
                    output += `${est.eventName}: ${est.value}<br/>`;
                return output;
            };

            if (!params.data) return `<strong>${params.name}</strong><br/>Não há dados para este estado`;

            return `<strong>${params.name}: ${params.data.value}</strong><br/>` + eventsSubTotalsString();
        }';
        $series = new Series();
        $series->name = $question->title;
        $series->type = 'map';
        $series->map = 'brazil';
        $series->data = $data;
        $series->label->emphasis->show = false;
        $series->label->emphasis->textStyle->color = '#fff';
        $series->roam = true;
        $series->scaleLimit->min = 1;
        $series->scaleLimit->max = 5;
        $series->itemStyle->normal->borderColor = '#bbb';
        $series->itemStyle->normal->areaColor = '#F5F6FA';
        $series->itemStyle->emphasis->areaColor = '#CC0000';
        $chart->addSeries($series);
        Config::addExtraScript('brmap.js', URL\URLGenerator::generateFileURL('includes/'));

        return '<div class="reportItemChart">' . $chart->render('map', ['style' => 'height: 500px; ']) . '</div>';
    }

    private function generateHTML(object $question) : string
    {
        $valuesCount = [];
        $totalCount = 0;

        if ($question->chartType !== "text")
        {
            for ($i = 0; $i < count($question->answers); $i++)
            {
                if (!isset($valuesCount[$question->answers[$i]['value']]))
                {
                    $valuesCount[$question->answers[$i]['value']] = [ 'total' => 0, 'eventsSubTotals' => [] ];
                }

                $valuesCount[$question->answers[$i]['value']]['total']++;
                $totalCount++;
                
                $foundEventSubTotals = array_filter($valuesCount[$question->answers[$i]['value']]['eventsSubTotals'], fn($est) => $est['eventName'] === $question->answers[$i]['eventName']);
                if (!empty($foundEventSubTotals))
                {
                    foreach ($valuesCount[$question->answers[$i]['value']]['eventsSubTotals'] as &$est)
                        if ($est['eventName'] === $question->answers[$i]['eventName'])
                            $est['value'] += 1;
                }
                else
                    $valuesCount[$question->answers[$i]['value']]['eventsSubTotals'][] = [ 'eventName' => $question->answers[$i]['eventName'], 'value' => 1 ];
                
            }
            ksort($valuesCount);
        }

        $output = '<div class="reportItemContainer">';
        $output .= "<h3>{$question->title}</h3>";
        $output .= '<div class="reportItemContainerFlex">';
        
        switch ($question->chartType)
        {
            case "pie":
                $output .= $this->generateInfosHTMLforPieChart($valuesCount, $totalCount);
                $data = [];
                foreach ($valuesCount as $k => $v)
                    $data[] = [ 'name' => $k, 'value' => $v['total'], 'eventsSubTotals' => $v['eventsSubTotals'] ];
                $output .= $this->generatePieChartHTML($question, $data);
                break;
            case "bar":
                $output .= $this->generateInfosHTMLforBarChart($valuesCount, $totalCount);
                $chartDataObjects = array_map(fn($countItem) => [ 'value' => $countItem['total'], 'eventsSubTotals' => $countItem['eventsSubTotals'] ], array_values($valuesCount));
                $output .= $this->generateBarChartHTML($question, array_keys($valuesCount), $chartDataObjects);
                break;
            case "map":
                $output .= $this->generateInfosHTMLforPieChart($valuesCount, $totalCount);
                $data = [];
                $maxValue = 0;
                foreach ($valuesCount as $k => $v)
                {
                    $data[] = ['name' => $k, 'value' => $v['total'], 'eventsSubTotals' => $v['eventsSubTotals'] ];
                    if ($v['total'] > $maxValue) $maxValue = $v['total'];
                }
                $output .= $this->generateMapChartHTML($question, $data, $maxValue);
                break;
            case "text":
                $output .= $this->generateInfosHTMLforText($question->answers);
                break;
        }
        
        $output .= "</div></div>";  

        return $output;
    }

    private function generateInfosHTMLforText(array $valuesAndEventNames) : string
    {
        $output = "";
        $output .= "<div class=\"reportItemInfos\">";
        foreach ($valuesAndEventNames as $valReg)
        {
            $output .= "<p>" . hsc($valReg['value']) . " <span> - <strong>Evento: " . hsc($valReg['eventName']) . "</strong></span>" . "</p>";
        }
        $output .= "</div>";
        return $output;
    }

    private function generateInfosHTMLforPieChart(array $valuesCount, int $totalCount) : string
    {
        $output = "";
        $output .= "<div class=\"reportItemInfos\">";
        foreach ($valuesCount as $val => $countsArray)
        {
            $output .= "<span class=\"reportItemInfosDataRowContainer\">";
            $output .= "<span class=\"reportItemInfosLabel\">";
            $output.= hsc($val . ": "); 
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
            $output .= "<span class=\"reportItemInfosLabel\">";
            $output.= hsc($val . ": "); 
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

    private function processData()
    {
        $questions = [];

        foreach ($this->subscriptionObjects as $subsObj)
        {
            foreach ($subsObj->getQuestions() as $item)
            {
                if (empty($item->value)) continue;

                $questionsFoundInQuestionsArray = array_filter($questions, fn($quest) => $quest->title === $item->formInput->label);
                $isAlreadyInQuestions = !empty($questionsFoundInQuestionsArray); 
                if (!$isAlreadyInQuestions)
                {
                    $question = new class{};
                    $question->title = $item->formInput->label;
                    $question->answers = [];
                    $questions[] = $question;
                }

                $questionFound = array_filter($questions, fn($quest) => $quest->title === $item->formInput->label);
                $questionFound = array_pop($questionFound);
                $currentQuestion = $questions[array_search($questionFound, $questions)];

                if ($item->formInput->type === 'text')
                {
                    $currentQuestion->chartType = 'text';
                    $currentQuestion->answers[] = [ 'eventName' => $subsObj->getOtherProperties()->eventName, 'value' => $item->value ];
                }
                else if ($item->formInput->type === 'checkList')
                {
                    $currentQuestion->chartType = 'bar';
                    $currentQuestion->answers = 
                    [...$currentQuestion->answers, 
                        ...(function($item, $eventName)
                        {
                            $separatedAnswers = explode(", ", $item->value);
                            return array_map( fn($a) => [ 'eventName' => $eventName, 'value' => $a ], $separatedAnswers);
                        })($item, $subsObj->getOtherProperties()->eventName) 
                    ];
                }
                else if ($item->formInput->type === 'date' && $item->identifier === 'birthDate')
                {
                    $currentQuestion->chartType = 'pie';
                    $currentQuestion->answers[] = [ 'eventName' => $subsObj->getOtherProperties()->eventName, 'value' => date_create($item->value)->format('Y') ];
                }
                else
                {
                    $currentQuestion->chartType = 'pie';
                    $currentQuestion->answers[] = [ 'eventName' => $subsObj->getOtherProperties()->eventName, 'value' => $item->value ];
                }

                if ($item->identifier === 'stateUf') $currentQuestion->chartType = 'map';
            }
        }

        $this->outputData = $questions;
    }
}