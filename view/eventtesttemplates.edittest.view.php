<style>
    .eventTestQuestionsBlock { counter-reset: head; }
    .eventTestQuestionsBlock > .eventQuestionItem::before 
    { 
        counter-increment: head;
        content: counter(head); 
    }

    .eventQuestionItem::before
    {
        font-weight: bold;
        color: #22B14C;
        display: block;
        position: absolute;
        background-color: white;
        text-align: center;
        left: -20px;
        top: -2px;
        width: 40px;
    }

    .eventQuestionItem
    { 
        margin: 0.8em 0.3em 0.8em 0.3em;
        padding-left: 0.8em;
        border-left: 2px solid black;
        position:relative;
    }

    .eventQuestionItem ol
    {
        list-style: lower-latin;
    }

    .moveQuestionsButtons { min-width: 25px; margin: 0; }
    .addOptionButton { min-width: 25px; display: block; }
    .addRemoveItemsButtons { margin: 0px; }
</style>
<script src="<?php echo URL\URLGenerator::generateFileURL("view/eventtesttemplates.edittest.view.js"); ?>"></script>

<!-- Begin: Item components templates -->

    <script>
        let lastQuestIndex = <?= count($testObj->questions) - 1 ?>;
    </script>
    <div id="itemQuestion" style="display:none;">
        <div class="eventQuestionItem" data-page-question-id="">
            <span class="formField"><label>Enunciado: <textarea class="txtQuestionText" rows="4"></textarea></label></span>
            <span class="formField"><label><input type="checkbox" class="chkRandomize"/> Randomizar alternativas</label></span>
            <ol>
                <li>
                    <span class="spanOptionInput">
                        <input type="text" size="40" class="txtOptionText"/>
                    </span>
                    <select class="selOptionType" onchange="SisEpi.Events.Tests.Templates.selOptionType_onChange(this)">
                        <option value="string" selected >Texto</option>
                        <option value="image">Imagem</option>
                    </select>
                    <label><input type="radio" class="radCorrectAnswerQuest" name="radCorrectAnswerQuest" /> Correta</label>
                    <button type="button" class="moveQuestionsButtons" onclick="SisEpi.Events.Tests.Templates.btnDeleteOption_onClick(this)">&times;</button>
                </li>
            </ol>
            <button type="button" class="addOptionButton" onclick="SisEpi.Events.Tests.Templates.btnAddOption_onClick(this)">+</button>

            <button type="button" class="moveQuestionsButtons" onclick="SisEpi.Events.Tests.Templates.btnMoveQuestionUp_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/up.png"); ?>" title="Mover para cima" alt="Mover para cima"/></button>
            <button type="button" class="moveQuestionsButtons" onclick="SisEpi.Events.Tests.Templates.btnMoveQuestionDown_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/down.png"); ?>" title="Mover para baixo" alt="Mover para baixo"/></button>
            <button type="button" class="addRemoveItemsButtons" onclick="SisEpi.Events.Tests.Templates.btnDeleteQuestion_onClick(this)">Excluir questão</button>
        </div>
    </div>
    <div id="questionOptionString" style="display: none;">
        <span class="spanOptionInput">
            <input type="text" size="40" class="txtOptionText"/>
        </span>
    </div>
    <div id="questionOptionImage" style="display:none;">
        <span class="spanOptionInput">
            <input type="file" class="fileOptionImage" accept="image/*" onchange="SisEpi.Events.Tests.Templates.fileOptionImage_onChange(this)" />
            <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="imgOptionImage" height="100" alt="Prévia de imagem"/>
        </span>
    </div>

<!-- End: Item components templates -->

<?php 

function writeSelectedStatus($property, $checkForOptionName)
{
    if (!isset($property)) return '';
    return (string)$property === (string)$checkForOptionName ? ' selected="selected" ' : '';
}

function writeCheckedStatus($property, $expectedValue)
{
    if (!isset($property)) return '';
    return (string)$property === (string)$expectedValue ? ' checked="checked" ' : '';
}
function writeQuestionHTML(object $question, int $questIndex)
{
    ?>
    <div class="eventQuestionItem" data-page-question-id="<?= $questIndex ?>">
       
        <span class="formField"><label>Enunciado: <textarea class="txtQuestionText" rows="4" required ><?= hsc($question->questText ?? '') ?></textarea></label></span>
        <span class="formField"><label><input type="checkbox" class="chkRandomize" <?= writeCheckedStatus($question->randomize, 1) ?>/> Randomizar alternativas</label></span>
        <ol>
            <?php foreach ($question->options as $iOpt => $option): ?>
            <li>
                <span class="spanOptionInput">
                <?php if ($option->type === "string"): ?>
                    <input type="text" size="40" class="txtOptionText" required value="<?= hscq($option->value) ?>"/>
                <?php elseif ($option->type === "image"): ?>
                    <input type="file" class="fileOptionImage" accept="image/*" onchange="SisEpi.Events.Tests.Templates.fileOptionImage_onChange(this)" />
                    <img src="<?= $option->value ?>" class="imgOptionImage" height="100" alt="Prévia de imagem"/>
                <?php endif; ?>
                </span>
                <select class="selOptionType" onchange="SisEpi.Events.Tests.Templates.selOptionType_onChange(this)">
                    <option value="string" <?= writeSelectedStatus($option->type, 'string') ?> >Texto</option>
                    <option value="image" <?= writeSelectedStatus($option->type, 'image') ?>>Imagem</option>
                </select>
                <label><input type="radio" class="radCorrectAnswerQuest" name="radCorrectAnswerQuest<?= $questIndex ?>" required <?= writeCheckedStatus($question->correctAnswer, $iOpt) ?>/> Correta</label>
                <button type="button" class="moveQuestionsButtons" onclick="SisEpi.Events.Tests.Templates.btnDeleteOption_onClick(this)">&times;</button>
            </li>
            <?php endforeach; ?>
        </ol>
        <button type="button" class="addOptionButton" onclick="SisEpi.Events.Tests.Templates.btnAddOption_onClick(this)">+</button>

        <button type="button" class="moveQuestionsButtons" onclick="SisEpi.Events.Tests.Templates.btnMoveQuestionUp_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/up.png"); ?>" title="Mover para cima" alt="Mover para cima"/></button>
        <button type="button" class="moveQuestionsButtons" onclick="SisEpi.Events.Tests.Templates.btnMoveQuestionDown_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/down.png"); ?>" title="Mover para baixo" alt="Mover para baixo"/></button>
        <button type="button" class="addRemoveItemsButtons" onclick="SisEpi.Events.Tests.Templates.btnDeleteQuestion_onClick(this)">Excluir questão</button>
        
    </div>
    <?php
}

    if (isset($testObj)): ?>
        
        <span class="formField">
            <label>Título da avaliação (visível ao público): <input type="text" class="txtTestTitle" value="<?= hscq($testObj->title ?? '') ?>"/></label>
        </span>
        <span class="formField">
            <label>Porcentagem mínima para aprovação: <input type="number" class="numMinPercentForApproval" min="1" max="100" step="1" value="<?= hscq($testObj->percentForApproval ?? '') ?>"/>%</label>
        </span>
        <span class="formField">
            <label>Carga horária desta avaliação (somada à carga do evento): <input type="number" class="numClassTime" min="1" step="1" value="<?= hscq($testObj->classTimeHours) ?>"/></label>
        </span>
        <span class="formField">
            <label><input type="checkbox" class="chkRandomizeQuestions" value="1" <?= writeCheckedStatus($testObj->randomizeQuestions, 1) ?>/> Randomizar questões</label>
        </span>

        <div class="eventTestQuestionsBlock">
            <?php foreach ($testObj->questions as $qi => $quest)
            {
                writeQuestionHTML($quest, $qi);
            }?>
        </div>
       <button type="button" class="addRemoveItemsButtons" onclick="SisEpi.Events.Tests.Templates.btnNewQuestion_onClick(this)">Nova questão</button>
    
    <?php endif; ?>
 