<?php

namespace SisEpi\Pub\Includes\GenerateView\EventTest;

function randomizeArray(array $arr) : array
{
    $randIntervalNumber = count($arr) - 0.5;
    $finalSequence = [];
    $array = $arr;

    while (count($array) > 0)
    {
        $randomized = floor((mt_rand() / mt_getrandmax()) * $randIntervalNumber);
        $finalSequence[] = $array[$randomized];
        array_splice($array, $randomized, 1);
        $randIntervalNumber--;
    }

    return $finalSequence;
}

function indexOf(object $object, array $array) : ?int
{
    $filtered = array_filter($array, fn($i) => $i == $object);
    $keys = array_keys($filtered);
    return array_shift($keys);
}

function writeTestFormFieldsHTML(object $testTemplate)
{ 
    ?>
    <style>
        .testBlock { background-color: #eee; margin-bottom: 0.5em; border-radius: 10px; padding: 0.5em; }
        .testBlock { counter-reset: testquestioncount }

        .testQuestion { padding: 0.3em; border-bottom: 1px solid darkgray; }

        .testBlock .testQuestion > .testQuestionText::before { counter-increment: testquestioncount; content: counter(testquestioncount) ". "; }

        .testQuestionText
        {
            display: block;
            font-size: 102%;
            font-weight: bold;
        }

        .testQuestion .questionOptions
        {
            list-style-type: lower-latin;
        }
    </style>
    <?php
    echo "<h3 class=\"centControl\">{$testTemplate->title}</h3>";

    echo "<h4>Atenção!</h4>";
    echo "<ul>";
    echo "<li>Você precisará acertar no mínimo {$testTemplate->percentForApproval}% desta avaliação para ser aprovado(a);</li>";
    echo "<li>Esta avaliação comporá {$testTemplate->classTimeHours} hora(s) da carga horária deste evento.</li>";
    echo "</ul>";

    echo "<hr/>";

    $questions = $testTemplate->randomizeQuestions ? randomizeArray($testTemplate->questions) : $testTemplate->questions;

    foreach ($questions as $i => $question)
    {
        writeQuestion($question, indexOf($question, $testTemplate->questions));
    }
}

function writeQuestion(object $question, int $originalIndex)
{
    echo "<div class=\"testQuestion\">";
    echo "<span class=\"testQuestionText\">" . nl2br(hsc($question->questText)) . "</span>";

    $options = $question->randomize ? randomizeArray($question->options) : $question->options;

    echo "<ol class=\"questionOptions\">";
    foreach ($options as $option)
    { 
        $optionOriginalIndex = indexOf($option, $question->options);
        ?>
        <?php if ($option->type === "string"): ?>
            <li><label><input type="radio" required name="testQuestions[<?= $originalIndex ?>]" value="<?= $optionOriginalIndex ?>" /><?= hsc($option->value) ?></label></li>
        <?php elseif ($option->type === "image"): ?>
            <li><label><input type="radio" required name="testQuestions[<?= $originalIndex ?>]" value="<?= $optionOriginalIndex ?>" /><img src="<?= $option->value ?>" alt="Alternativa em forma de imagem" /></label></li>
        <?php endif; ?>
    <?php
    }
    echo "</ol>";

    echo "</div>";
}