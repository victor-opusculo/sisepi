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

    <div id="itemQuestion" style="display:none;">
        <div class="eventQuestionItem">
            <span class="formField"><label>Enunciado: <input type="text" class="txtQuestionText" size="40" maxlength="280" /></label></span>
            <span class="formField"><label><input type="checkbox" class="chkRandomize"/> Randomizar alternativas</label></span>
            <ol>
                <li>
                    <input type="text" size="40" class="txtOptionText"/>
                    <select class="selOptionType">
                        <option value="string" selected >Texto</option>
                        <option value="image">Imagem</option>
                    </select>
                    <button type="button" class="moveItemsButtons" onclick="btnDeleteOption_onClick(this)">&times;</button>
                </li>
            </ol>
            <button type="button" class="addOptionButton" onclick="btnAddOption_onClick(this)">+</button>

            <button type="button" class="moveQuestionsButtons" onclick="btnMoveQuestionUp_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/up.png"); ?>" title="Mover para cima" alt="Mover para cima"/></button>
            <button type="button" class="moveQuestionsButtons" onclick="btnMoveQuestionDown_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/down.png"); ?>" title="Mover para baixo" alt="Mover para baixo"/></button>
            <button type="button" class="addRemoveItemsButtons" onclick="btnDeleteQuestion_onClick(this)">Excluir item</button>
        </div>
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
    <div class="eventQuestionItem">
       
        <span class="formField"><label>Enunciado: <input type="text" class="txtQuestionText" size="40" maxlength="280" value="<?= hscq($question->questText ?? '') ?>"/></label></span>
        <span class="formField"><label><input type="checkbox" class="chkRandomize" <?= writeCheckedStatus($question->randomize, 1) ?>/> Randomizar alternativas</label></span>
        <ol>
            <?php foreach ($question->options as $iOpt => $option): ?>
            <li>
                <?php if ($option->type === "string"): ?>
                    <input type="text" size="40" class="txtOptionText" value="<?= hscq($option->value) ?>"/>
                <?php elseif ($option->type === "image"): ?>
                    <input type="file" class="fileOptionImage" accept="image/*" />
                    <img src="<?= $option->value ?>" class="imgOptionImage" height="128" />
                <?php endif; ?>
                <select class="selOptionType">
                    <option value="string" <?= writeSelectedStatus($option->type, 'string') ?> >Texto</option>
                    <option value="image" <?= writeSelectedStatus($option->type, 'image') ?>>Imagem</option>
                </select>
                <label><input type="radio" class="radCorrectAnswerQuest" name="radCorrectAnswerQuest<?= $questIndex ?>" value="<?= $iOpt ?>" <?= writeCheckedStatus($question->correctAnswer, $iOpt) ?>/> Correta</label>
                <button type="button" class="moveQuestionsButtons" onclick="btnDeleteOption_onClick(this)">&times;</button>
            </li>
            <?php endforeach; ?>
        </ol>
        <button type="button" class="addOptionButton" onclick="btnAddOption_onClick(this)">+</button>

        <button type="button" class="moveQuestionsButtons" onclick="btnMoveQuestionUp_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/up.png"); ?>" title="Mover para cima" alt="Mover para cima"/></button>
        <button type="button" class="moveQuestionsButtons" onclick="btnMoveQuestionDown_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/down.png"); ?>" title="Mover para baixo" alt="Mover para baixo"/></button>
        <button type="button" class="addRemoveItemsButtons" onclick="btnDeleteQuestion_onClick(this)">Excluir item</button>
        
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
       <button type="button" class="addRemoveItemsButtons" onclick="btnNewQuestion_onClick(this)">Novo item</button>
    
    <?php endif; ?>
 