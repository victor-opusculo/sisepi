<style>
    .eventSurveyHeadBlock { counter-reset: head; }
    .eventSurveyHeadBlock > .eventSurveyItem::before 
    { 
        counter-increment: head;
        content: counter(head); 
    }

    .eventSurveyBodyBlock { counter-reset: body; }
    .eventSurveyBodyBlock > .eventSurveyItem::before 
    { 
        counter-increment: body;
        content: counter(body); 
    }

    .eventSurveyFootBlock { counter-reset: foot; }
    .eventSurveyFootBlock > .eventSurveyItem::before 
    { 
        counter-increment: foot;
        content: counter(foot); 
    }

    .eventSurveyItem::before
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

    .eventSurveyItem
    { 
        margin: 0.8em 0.3em 0.8em 0.3em;
        padding-left: 0.8em;
        border-left: 2px solid black;
        position:relative;
    }

    .moveItemsButtons { min-width: 25px; margin: 0; }
    .addSubItemsButton { min-width: 25px; display: block; }
    .addRemoveItemsButtons { margin: 0px; }
</style>
<script src="<?php echo URL\URLGenerator::generateFileURL("view/eventsurveytemplates.editsurvey.view.js"); ?>"></script>

<!-- Begin: Item components templates -->

    <div id="itemChecklist" style="display:none;">
        <ol>
            <li>
                <input type="text" size="40" name="subItemName"/>
                <button type="button" class="moveItemsButtons" onclick="btnDeleteSubItem_onClick(this)">&times;</button>
            </li>
        </ol>
        <button type="button" class="addSubItemsButton" onclick="btnAddSubItem_onClick(this)">+</button>
    </div>

    <div id="itemTemplate" style="display:none;">
        <div class="eventSurveyItem">
            <span class="formField"><label>Título: <input type="text" name="itemName" size="40" maxlength="255" /></label></span>
            <span class="formField">
                <label>Tipo: 
                    <select name="itemType" oninput="cmbItemType_onInput(this)">
                        <option value="yesNo" selected="selected">Sim/Não</option>
                        <option value="fiveStarRating">Nota de 1 a 5</option>
                        <option value="checkList">Lista de checkboxes</option>
                        <option value="shortText">Texto curto</option>
                        <option value="textArea">Texto longo</option>
                    </select>
                </label>
            </span>
            <span class="formField">
                <label><input type="checkbox" name="itemOptional" /> Opcional</label>
            </span>

            <span class="eventSurveyItem_checklist"></span>

            <button type="button" class="moveItemsButtons" onclick="btnMoveItemUp_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/up.png"); ?>" title="Mover para cima" alt="Mover para cima"/></button>
            <button type="button" class="moveItemsButtons" onclick="btnMoveItemDown_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/down.png"); ?>" title="Mover para baixo" alt="Mover para baixo"/></button>
            <button type="button" class="addRemoveItemsButtons" onclick="btnDeleteItem_onClick(this)">Excluir item</button>
        </div>
    </div>

<!-- End: Item components templates -->

<?php 

function writeSelectedStatus($property, $checkForOptionName)
{
    if (!isset($property)) return '';
    return (string)$property === (string)$checkForOptionName ? ' selected="selected" ' : '';
}

function writeDisabledStatus($condition)
{
    return $condition ? ' disabled="disabled" ' : '';
}
function writeItemHTML($item)
{
    if (!isset($item->type)) $item->type = "yesNo";

    ?>
    <div class="eventSurveyItem">
        <span class="formField"><label>Título: <input type="text" name="itemName" required="required" size="40" maxlength="255" value="<?php echo hscq($item->title) ?? "";  ?>" /></label></span>
        <span class="formField">
            <label>Tipo: 
                <select name="itemType" oninput="cmbItemType_onInput(this)">
                    <option value="yesNo" <?php echo writeSelectedStatus($item->type, "yesNo"); ?>>Sim/Não</option>
                    <option value="fiveStarRating" <?php echo writeSelectedStatus($item->type, "fiveStarRating"); ?>>Nota de 1 a 5</option>
                    <option value="checkList" <?php echo writeSelectedStatus($item->type, "checkList"); ?>>Lista de checkboxes</option>
                    <option value="shortText" <?php echo writeSelectedStatus($item->type, "shortText"); ?>>Texto curto</option>
                    <option value="textArea" <?php echo writeSelectedStatus($item->type, "textArea"); ?>>Texto longo</option>
                </select>
            </label>
        </span>
        <span class="formField">
            <label><input type="checkbox" name="itemOptional" 
            <?php echo !empty($item->optional) && $item->optional ? ' checked="checked" ' : ''; ?> 
            <?php echo writeDisabledStatus($item->type === 'checkList'); ?>
            /> Opcional</label>
        </span>

        <span class="eventSurveyItem_checklist">
        <?php if (isset($item->checkList)): ?>
            <ol>
            <?php foreach ($item->checkList as $subItem): ?>
                <li>
                    <input type="text" size="40" name="subItemName" required="required" value="<?php echo hscq($subItem->name); ?>"/>
                    <button type="button" class="moveItemsButtons" onclick="btnDeleteSubItem_onClick(this)">&times;</button>
                </li>
            <?php endforeach; ?>
            </ol>
            <button type="button" class="addSubItemsButton" onclick="btnAddSubItem_onClick(this)">+</button>
        <?php endif; ?>
        </span>

        <button type="button" class="moveItemsButtons" onclick="btnMoveItemUp_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/up.png"); ?>" title="Mover para cima" alt="Mover para cima"/></button>
        <button type="button" class="moveItemsButtons" onclick="btnMoveItemDown_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/down.png"); ?>" title="Mover para baixo" alt="Mover para baixo"/></button>
        <button type="button" class="addRemoveItemsButtons" onclick="btnDeleteItem_onClick(this)">Excluir item</button>
        
    </div>
    <?php
}

    if (isset($surveyObj->head))
    {
        echo '<h2>Participante</h2><div class="eventSurveyHeadBlock">';
        foreach ($surveyObj->head as $item)
            writeItemHTML($item);
        echo '</div>';
        echo '<button type="button" class="addRemoveItemsButtons" onclick="btnNewItem_onClick(this)">Novo item</button>';
    }

    if (isset($surveyObj->body))
    {
        echo '<h2>Avaliação do Evento</h2><div class="eventSurveyBodyBlock">';
        foreach ($surveyObj->body as $item)
            writeItemHTML($item);
        echo '</div>';
        echo '<button type="button" class="addRemoveItemsButtons" onclick="btnNewItem_onClick(this)">Novo item</button>';
    }

    if (isset($surveyObj->foot))
    {
        echo '<h2>Melhorias e Sugestões</h2><div class="eventSurveyFootBlock">';
        foreach ($surveyObj->foot as $item)
            writeItemHTML($item);
        echo '</div>';
        echo '<button type="button" class="addRemoveItemsButtons" onclick="btnNewItem_onClick(this)">Novo item</button>';
    }
    

?>