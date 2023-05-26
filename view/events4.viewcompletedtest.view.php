<?php if (isset($testObj)): ?>
    <style>
        .approvedResult { color: green; }
        .reprovedResult { color: red; }
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

        .questionOptions li.studentAnswer
        {
             border: 2px solid red; 
             border-radius: 5px; 
        }

        .questionOptions li.correctAnswer::after { content: "(Correta)"; font-weight: bold; margin-left: 0.2em; }

        .randomizeInfo { font-size: small; text-align: right; display: block; margin: 0.5em 0 0.5em 0; }
    </style>

    <?php $testData = json_decode($testObj->testData ?? ''); 

    $writeOptionContent = function(object $option)
    {
        switch ($option->type)
        {
            case "image":
                echo "<img height=\"80\" src=\"{$option->value}\" alt=\"Alternativa em imagem\" />"; break;
            case "string": default: 
                echo hsc($option->value); break;
        }
    };

    if (!empty($testData)): ?>

        <div class="viewDataFrame">
            <label>ID: </label><?= $testObj->id ?><br/>
            <label>Evento vinculado: </label><a href="<?= URL\URLGenerator::generateSystemURL('events', 'view', $testObj->eventId) ?>"><?= hsc($testObj->getOtherProperties()->eventName) ?></a><br/>
            <label>Título: </label><?= hsc($testData->title) ?><br/>
            <label>Carga horária deste teste: </label><?= hsc($testData->classTimeHours . 'h') ?><br/>
            <label>Randomizar questões: </label><?= $testData->randomizeQuestions ? 'Sim' : 'Não' ?><br/>
            <label>Nota mínima para aprovação: </label><?= hsc($testData->percentForApproval) ?>%<br/>
            <br/>
            <label>Participante: </label><?= hsc($testObj->studentName) ?><br/>
            <label>Nota obtida: </label><?= hsc($testData->grade) ?>%</br/>
            <label>Data de envio: </label><?= hsc(date_create($testObj->sentDateTime)->format('d/m/Y H:i:s')) ?><br/>
            <label>Resultado: </label><?= $testData->grade >= $testData->percentForApproval ? '<span class="approvedResult">Aprovado!</span>' : '<span class="reprovedResult">Reprovado!</span>' ?><br/>
        </div>

        <div class="testBlock">
            <?php foreach ($testData->questions as $question): ?>
                <div class="testQuestion">
                    <span class="testQuestionText"><?= hsc($question->questText) ?></span>
                    <span class="randomizeInfo">Randomizar alternativas: <?= $question->randomize ? 'Sim' : 'Não' ?></span>
                    <ol class="questionOptions">
                        <?php foreach ($question->options as $iOpt => $opt): ?>
                            <li class="<?= $question->studentAnswer === $iOpt ? 'studentAnswer' : '' ?> <?= $question->correctAnswer === $iOpt ? 'correctAnswer' : '' ?>">
                                <?php $writeOptionContent($opt); ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>

                </div>
            <?php endforeach; ?>
        </div>

        <div class="editDeleteButtonsFrame">
            <ul>
                <li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("events4", "deletesingletest", $testObj->id); ?>">Excluir avaliação</a></li>
            </ul>
        </div>


    <?php endif; ?>
<?php endif; ?>