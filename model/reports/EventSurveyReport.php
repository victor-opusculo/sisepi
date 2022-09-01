<?php
require_once __DIR__ . "/Report.php";
require_once __DIR__ . "/../../includes/common.php";
require_once __DIR__ . "/../database/reports.database.php";
require_once __DIR__ . "/../AnsweredEventSurvey.php";
require_once __DIR__ . "/../../vendor/autoload.php";

use Hisune\EchartsPHP\ECharts;
use \Hisune\EchartsPHP\Config;
use \Hisune\EchartsPHP\Doc\IDE\Series;

final class EventSurveyReport extends Report
{
    private ?array $rawDataRows;
    private ?array $outputData;
    private int $htmlElementIdNumber = 0;

    public function __construct(array $eventIds, ?mysqli $optConnection = null)
    {
        $this->rawDataRows = getEventSurveysReport($eventIds, $optConnection);
        if (empty($this->rawDataRows))
            throw new Exception("Não há dados disponíveis para os eventos selecionados.");

        $this->processData();
    }

    public function getReportItemsHTML()
    {
        foreach ($this->outputData as $item)
        {
            yield $this->generateHTML($item);
        }
    }

    private function generatePieChartHTML(object $question, array $data) : string
    {
        $chart = new ECharts();
        $chart->legend->orient = 'vertical';
        $chart->legend->left = 'left';
        $chart->tooltip->trigger = 'item';
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
        $chart->tooltip->trigger = 'axis';
        $chart->tooltip->axisPointer = [ 'type' => 'shadow' ];
        $chart->color = self::chartColors;
        $chart->setJsVar('chart' . $this->htmlElementIdNumber);

        $series = new Series();
        $series->name = $question->title;
        $series->data = $seriesData;
        $series->type = 'bar';
        $series->barWidth = '40%';
        $chart->addSeries($series);

        return '<div class="reportItemChart">' . $chart->render('chart' . $this->htmlElementIdNumber++, ['style' => 'height: 350px']) . '</div>';
    }

    private function generateHTML(object $question) : string
    {
        $valuesCount = [];
        $totalCount = 0;

        if ($question->chartType !== "text")
        {
            for ($i = 0; $i < count($question->answers); $i++)
            {
                if (!isset($valuesCount[$question->answers[$i]]))
                    $valuesCount[$question->answers[$i]] = 0;

                $valuesCount[$question->answers[$i]]++;
                $totalCount++;
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
                    $data[] = [ 'name' => $k, 'value' => $v ];
                $output .= $this->generatePieChartHTML($question, $data);
                break;
            case "pieYesNo":
                $output .= $this->generateInfosHTMLforPieChart($valuesCount, $totalCount);
                $data = [];
                foreach ($valuesCount as $k => $v)
                    $data[] = [ 'name' => $k, 'value' => $v, 'itemStyle' => 
                    [ 
                        'color' => $k === 'Não' ? '#c23531' :
                        ($k === 'Sim' ? '#22B14C' : '#395a5e')
                    ] ];
                $output .= $this->generatePieChartHTML($question, $data);
                break;
            case "bar":
                $output .= $this->generateInfosHTMLforBarChart($valuesCount, $totalCount);
                $output .= $this->generateBarChartHTML($question, array_keys($valuesCount), array_values($valuesCount));
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
            $output .= "<p>" . hsc($valReg['value']) . "</p>";
            $output .= "<p/><strong>Evento: " . hsc($valReg['eventName']) . "</strong></p>"; 
            $output .= "<hr/>";
        }
        $output .= "</div>";
        return $output;
    }

    private function generateInfosHTMLforPieChart(array $valuesCount, int $totalCount) : string
    {
        $output = "";
        $output .= "<div class=\"reportItemInfos\">";
        foreach ($valuesCount as $val => $count)
        {
            $output .= "<span class=\"reportItemInfosDataRowContainer\">";
            $output .= "<span class=\"reportItemInfosLabel\">";
            $output.= hsc($val . ": "); 
            $output .= "</span>";
            $output .= "<span class=\"reportItemInfosValue\">";
            $output.= hsc($count . " (" . number_format(($count * 100) / $totalCount, 2, ',', '') . "%)"); 
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
        foreach ($valuesCount as $val => $count)
        {
            $output .= "<span class=\"reportItemInfosDataRowContainer\">";
            $output .= "<span class=\"reportItemInfosLabel\">";
            $output.= hsc($val . ": "); 
            $output .= "</span>";
            $output .= "<span class=\"reportItemInfosValue\">";
            $output.= hsc($count); 
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

        foreach ($this->rawDataRows as $sdr)
        {
            $cline = new AnsweredEventSurvey(json_decode($sdr['surveyJson']));
            foreach ($cline->allItemsAsObject() as $item)
            {
                if (empty($item->formattedAnswer)) continue;

                $questionsFoundInQuestionsArray = array_filter($questions, fn($quest) => $quest->title === $item->title);
                $isAlreadyInQuestions = !empty($questionsFoundInQuestionsArray); 
                if (!$isAlreadyInQuestions)
                {
                    $question = new class{};
                    $question->title = $item->title;
                    $question->answers = [];
                    $questions[] = $question;
                }

                $questionFound = array_filter($questions, fn($quest) => $quest->title === $item->title);
                $questionFound = array_pop($questionFound);
                $currentQuestion = $questions[array_search($questionFound, $questions)];
                
                if ($item->type === 'textArea' || $item->type === 'shortText')
                {
                    $currentQuestion->chartType = 'text';
                    $currentQuestion->answers[] = [ 'eventName' => $sdr['eventName'], 'value' => $item->formattedAnswer ];
                }
                else if ($item->type === 'checkList')
                {
                    $currentQuestion->chartType = 'bar';
                    $currentQuestion->answers = [...$currentQuestion->answers, ...explode(", ", $item->formattedAnswer)];
                }
                else if ($item->type === 'yesNo')
                {
                    $currentQuestion->chartType = 'pieYesNo';
                    $currentQuestion->answers[] = $item->formattedAnswer;
                }
                else
                {
                    $currentQuestion->chartType = 'pie';
                    $currentQuestion->answers[] = $item->formattedAnswer;
                }
            }
        }

        $this->outputData = $questions;
    }
}