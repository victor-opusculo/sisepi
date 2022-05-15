<style>
    .eventChecklistPreEventBlock { counter-reset: preevent; }
    .eventChecklistPreEventBlock > .eventChecklistItem::before 
    { 
        counter-increment: preevent;
        content: counter(preevent); 
    }

    .eventChecklistEventDateBlock { counter-reset: eventdate; }
    .eventChecklistEventDateBlock > .eventChecklistItem::before 
    { 
        counter-increment: eventdate;
        content: counter(eventdate); 
    }

    .eventChecklistPostEventBlock { counter-reset: postevent; }
    .eventChecklistPostEventBlock > .eventChecklistItem::before 
    { 
        counter-increment: postevent;
        content: counter(postevent); 
    }

    .eventChecklistItem::before
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

    .eventChecklistItem
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
<script src="<?php echo URL\URLGenerator::generateFileURL("view/eventchecklisttemplates.editchecklist.view.js"); ?>"></script>

<!-- Begin: Item components templates -->

    <div id="itemOptionalCheckbox" style="display:none;">
        <span class="formField"><label><input type="checkbox" value="1" name="itemOptional"/> Opcional</label></span>
    </div>

    <div id="itemResponsibleUser" style="display:none;">
        <label>Responsável:
            <select name="itemResponsible">
                <option value="0">(Indefinido)</option>
                <?php foreach ($responsibleUsersList as $u): ?>
                    <option value="<?php echo $u['id']; ?>"><?php echo $u['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <div id="itemSubChecklist" style="display:none;">
        <ol>
            <li>
                <input type="text" size="40" name="subItemName"/>
                <input type="hidden" name="subItemValue"/>
                <label><input type="checkbox" value="1" name="subItemOptional"/> Opcional</label>
                <button type="button" class="moveItemsButtons" onclick="btnDeleteSubItem_onClick(this)">X</button>
            </li>
        </ol>
        <button type="button" class="addSubItemsButton" onclick="btnAddSubItem_onClick(this)">+</button>
    </div>

    <div id="itemSubChecklistWithResponsibleUser" style="display:none;">
        <ol>
            <li>
                <input type="text" size="40" name="subItemName"/>
                <input type="hidden" name="subItemValue"/>
                <label><input type="checkbox" value="1" name="subItemOptional"/> Opcional</label>
                <label>| Responsável:
                    <select name="subItemResponsible">
                        <option value="0">(Indefinido)</option>
                        <?php foreach ($responsibleUsersList as $u): ?>
                            <option value="<?php echo $u['id']; ?>"><?php echo $u['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="button" class="moveItemsButtons" onclick="btnDeleteSubItem_onClick(this)">X</button>
            </li>
        </ol>
        <button type="button" class="addSubItemsButton" onclick="btnAddSubItem_onClick(this)">+</button>
    </div>

    <div id="itemTemplate" style="display:none;">
        <div class="eventChecklistItem">
            <span class="formField"><label>Título: <input type="text" name="itemName" size="40" maxlength="255" /></label></span>
            <span class="formField">
                <label>Tipo: 
                    <select name="itemType" oninput="cmbItemType_onInput(this)">
                        <option value="check" selected="selected">Checkbox simples</option>
                        <option value="checkList">Lista de checkboxes</option>
                        <option value="checkListWithResponsibleUser">Lista de checkboxes com nome de responsável para cada</option>
                        <option value="text">Texto</option>
                    </select>
                </label>
            </span>

            <span class="eventChecklistItem_subChecklist"></span>

            <span class="eventCheckListItem_optionalCheckBoxBlock">
                <span class="formField"><label><input type="checkbox" value="1" name="itemOptional"/> Opcional</label></span>
            </span>

            <span class="eventCheckListItem_responsibleUserBlock">
                <label>Responsável:
                    <select name="itemResponsible">
                        <option value="0">(Indefinido)</option>
                        <?php foreach ($responsibleUsersList as $u): ?>
                            <option value="<?php echo $u['id']; ?>"><?php echo $u['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </span>

            <button type="button" class="moveItemsButtons" onclick="btnMoveItemUp_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/up.png"); ?>" title="Mover para cima" alt="Mover para cima"/></button>
            <button type="button" class="moveItemsButtons" onclick="btnMoveItemDown_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/down.png"); ?>" title="Mover para baixo" alt="Mover para baixo"/></button>
            <button type="button" class="addRemoveItemsButtons" onclick="btnDeleteItem_onClick(this)">Excluir item</button>

            <input type="hidden" name="itemValue"/>
        </div>
    </div>

<!-- End: Item components templates -->

<?php 

function writeSelectedStatus($property, $checkForOptionName)
{
    if (!isset($property)) return '';
    return (string)$property === (string)$checkForOptionName ? ' selected="selected" ' : '';
}

function writeItemHTML($item, $responsibleUsersList)
{
    if (!isset($item->type)) $item->type = "check";
    if (!isset($item->optional)) $item->optional = false;
    ?>
    <div class="eventChecklistItem">
        <span class="formField"><label>Título: <input type="text" name="itemName" required="required" size="40" maxlength="255" value="<?php echo hscq($item->title) ?? "";  ?>" /></label></span>
        <span class="formField">
            <label>Tipo: 
                <select name="itemType" oninput="cmbItemType_onInput(this)">
                    <option value="check" <?php echo writeSelectedStatus($item->type, "check"); ?>>Checkbox simples</option>
                    <option value="checkList" <?php echo writeSelectedStatus($item->type, "checkList"); ?>>Lista de checkboxes</option>
                    <option value="checkListWithResponsibleUser" <?php echo writeSelectedStatus($item->type, "checkListWithResponsibleUser"); ?>>Lista de checkboxes com nome de responsável para cada</option>
                    <option value="text" <?php echo writeSelectedStatus($item->type, "text"); ?>>Texto</option>
                </select>
            </label>
        </span>

        <span class="eventChecklistItem_subChecklist">
        <?php if (isset($item->checkList)): ?>
            <ol>
            <?php foreach ($item->checkList as $subItem): ?>
                <li>
                    <input type="text" size="40" name="subItemName" required="required" value="<?php echo hscq($subItem->name); ?>"/>
                    <input type="hidden" name="subItemValue" value="<?php echo isset($subItem->value) ? $subItem->value : ''; ?>"/>
                    <label><input type="checkbox" name="subItemOptional" value="1" <?php echo $subItem->optional ? ' checked="checked" ' : ''; ?>/> Opcional</label>
                    <?php if ($item->type === "checkListWithResponsibleUser"): ?>
                        <label>| Responsável:
                            <select name="subItemResponsible">
                                <option value="0">(Indefinido)</option>
                                <?php foreach ($responsibleUsersList as $u): ?>
                                    <option value="<?php echo $u['id']; ?>" <?php echo writeSelectedStatus($subItem->responsibleUser, $u['id']); ?>><?php echo $u['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    <?php endif; ?>
                    <button type="button" class="moveItemsButtons" onclick="btnDeleteSubItem_onClick(this)">X</button>
                </li>
            <?php endforeach; ?>
            </ol>
            <button type="button" class="addSubItemsButton" onclick="btnAddSubItem_onClick(this)">+</button>
        <?php endif; ?>
        </span>

        <span class="eventCheckListItem_optionalCheckBoxBlock">
        <?php if ($item->type !== "checkList" && $item->type !== "checkListWithResponsibleUser"): ?>
            <span class="formField"><label><input type="checkbox" name="itemOptional" value="1" <?php echo $item->optional ? ' checked="checked" ' : ''; ?>/> Opcional</label></span>
        <?php endif; ?>
        </span>

        <span class="eventCheckListItem_responsibleUserBlock">
        <?php if ($item->type !== "checkListWithResponsibleUser"): ?>
            <label>Responsável:
                <select name="itemResponsible">
                    <option value="0">(Indefinido)</option>
                    <?php foreach ($responsibleUsersList as $u): ?>
                        <option value="<?php echo $u['id']; ?>" <?php echo writeSelectedStatus($item->responsibleUser, $u['id']); ?>><?php echo $u['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        <?php endif; ?>
        </span>

        <button type="button" class="moveItemsButtons" onclick="btnMoveItemUp_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/up.png"); ?>" title="Mover para cima" alt="Mover para cima"/></button>
        <button type="button" class="moveItemsButtons" onclick="btnMoveItemDown_onClick(this)"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/down.png"); ?>" title="Mover para baixo" alt="Mover para baixo"/></button>
        <button type="button" class="addRemoveItemsButtons" onclick="btnDeleteItem_onClick(this)">Excluir item</button>

        <input type="hidden" name="itemValue" value="<?php echo isset($item->value) ? $item->value : ''; ?>"/>
    </div>
    <?php
}

    if (isset($checklistObj->preevent))
    {
        echo '<h2>Pré-evento</h2><div class="eventChecklistPreEventBlock">';
        foreach ($checklistObj->preevent as $item)
            writeItemHTML($item, $responsibleUsersList);
        echo '</div>';
        echo '<button type="button" class="addRemoveItemsButtons" onclick="btnNewItem_onClick(this)">Novo item</button>';
    }

    if (isset($checklistObj->eventdate))
    {
        echo '<h2>Data de evento</h2><div class="eventChecklistEventDateBlock">';
        foreach ($checklistObj->eventdate as $item)
            writeItemHTML($item, $responsibleUsersList);
        echo '</div>';
        echo '<button type="button" class="addRemoveItemsButtons" onclick="btnNewItem_onClick(this)">Novo item</button>';
    }

    if (isset($checklistObj->postevent))
    {
        echo '<h2>Pós-evento</h2><div class="eventChecklistPostEventBlock">';
        foreach ($checklistObj->postevent as $item)
            writeItemHTML($item, $responsibleUsersList);
        echo '</div>';
        echo '<button type="button" class="addRemoveItemsButtons" onclick="btnNewItem_onClick(this)">Novo item</button>';
    }
    

?>