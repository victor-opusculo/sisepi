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

    .itemTitle 
    { 
        display: block;
        font-size: 1em;
        font-weight: bold;
    } 

    .eventChecklistItem_subChecklist ol li { margin-bottom: 0.8em; }
    .responsibleUserSpan { display: block; }
</style>

<script>
    const postURL = '<?php echo URL\URLGenerator::generateFileURL("post/eventchecklists.view.post.php"); ?>';
</script>
<script src="<?php echo URL\URLGenerator::generateFileURL("view/eventchecklists.view.view.js"); ?>" ></script>

<?php if (isset($eventOrEventDateInfos))
{ ?>
    <div class="viewDataFrame">
    <?php switch ($eventOrEventDateInfos['_entityType'])
    {
        case 'event': ?>
            <label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('events', 'view', $eventOrEventDateInfos['id']); ?>"><?php echo $eventOrEventDateInfos['name']; ?></a>
        <?php break;
        case 'eventdate': ?>
            <label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('events', 'view', $eventOrEventDateInfos['eventId']); ?>"><?php echo $eventOrEventDateInfos['eventName']; ?></a>
            <br/>
            <label>Nome/Conteúdo: </label><?php echo $eventOrEventDateInfos['eventDateName']; ?><br/>
            <label>Data: </label><?php echo (new DateTime($eventOrEventDateInfos['date']))->format('d/m/Y'); ?><br/>
            <label>Horário: </label><?php echo $eventOrEventDateInfos['beginTime'] . ' - ' . $eventOrEventDateInfos['endTime']; ?><br/>
        <?php break;
    } ?>
    </div>
<?php } ?>

<?php 
function writeCheckedStatus($property, $checkForValue)
{
    if (!isset($property)) return '';
    return (string)$property === (string)$checkForValue ? ' checked="checked" ' : '';
}

function writeDisabledStatus($propertyValue)
{
    return (string)$propertyValue === '2' ? ' disabled="disabled" ' : '';
}

function writeResponsibleUserName($userId, $responsibleUsersList)
{
    if ((int)$userId === 0) return 'Indefinido';

    foreach ($responsibleUsersList as $u)
        if ((int)$u['id'] === (int)$userId)
            return $u['name'];

    return 'Indefinido';
};

function writeItemHTML($item, $index, $blockName, $responsibleUsersList)
{
    if (!isset($item->type)) $item->type = "check";
    if (!isset($item->optional)) $item->optional = false;
    ?>
    <div class="eventChecklistItem">
        <span class="itemTitle"><?php echo hsc($item->title) ?? ""; ?></span>

        <?php if ($item->type === 'check'): ?>
            <label><input type="checkbox" class="regularCheck" data-checklist-path="<?php echo '$.' . $blockName . "[$index]" ?>" value="1" onchange="regularCheck_onChange(event, this)" <?php echo writeCheckedStatus($item->value ?? '', 1) . writeDisabledStatus($item->value ?? ''); ?>/> Feito</label>
            <?php if ($item->optional): ?>
                <label><input type="checkbox" class="doesNotApplyCheck" data-checklist-path="<?php echo '$.' . $blockName . "[$index]" ?>" value="2" onchange="doesNotApplyCheck_onChange(event, this)" <?php echo writeCheckedStatus($item->value ?? '', 2); ?>/> Não aplicável</label>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($item->type === 'text'): ?>
            <input type="text" size="40" maxlength="280" class="textInput" data-checklist-path="<?php echo '$.' . $blockName . "[$index]" ?>" value="<?php echo $item->value ?? ''; ?>" />
            <button type="button" onclick="btnSaveTextInputValue_onClick(event, this)">Salvar</button>
        <?php endif; ?>

        <span class="eventChecklistItem_subChecklist">
        <?php if (($item->type === 'checkList' || $item->type === 'checkListWithResponsibleUser') && isset($item->checkList)): ?>
            <ol>
            <?php foreach ($item->checkList as $siIndex => $subItem): ?>
                <li>
                    <span><?php echo hsc($subItem->name); ?></span><br/>
                    <label><input type="checkbox" class="regularCheck" data-checklist-path="<?php echo '$.' . $blockName . "[$index].checkList[$siIndex]" ?>" value="1" onchange="regularCheck_onChange(event, this)" <?php echo writeCheckedStatus($subItem->value ?? '', 1) . writeDisabledStatus($subItem->value ?? ''); ?>/> Feito</label>
                    <?php if ($subItem->optional): ?>
                        <label><input type="checkbox" class="doesNotApplyCheck" data-checklist-path="<?php echo '$.' . $blockName . "[$index].checkList[$siIndex]" ?>" value="2" onchange="doesNotApplyCheck_onChange(event, this)" <?php echo writeCheckedStatus($subItem->value ?? '', 2); ?>/> Não aplicável</label>
                    <?php endif; ?>

                    <?php if ($item->type === "checkListWithResponsibleUser"): ?>
                        <span>| Responsável: <?php echo writeResponsibleUserName($subItem->responsibleUser, $responsibleUsersList); ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
            </ol>
        <?php endif; ?>
        </span>

        <span class="eventCheckListItem_responsibleUserBlock">
        <?php if ($item->type !== "checkListWithResponsibleUser"): ?>
            <span class="responsibleUserSpan">Responsável: <?php echo writeResponsibleUserName($item->responsibleUser, $responsibleUsersList); ?></span>
        <?php endif; ?>
        </span>
    </div>
    <?php
}
if (isset($checklistDataObj))
{
    echo '<input type="hidden" id="checklistId" value="' . $checklistDataObj->id . '"/>';
    if (isset($checklistDataObj->checklistJson->preevent))
    {
        echo '<h2>Pré-evento</h2><div class="eventChecklistPreEventBlock">';
        for ($i = 0; $i < count($checklistDataObj->checklistJson->preevent); $i++)
            writeItemHTML($checklistDataObj->checklistJson->preevent[$i], $i, 'preevent', $responsibleUsersList);
        echo '</div>';
    }

    if (isset($checklistDataObj->checklistJson->eventdate))
    {
        echo '<h2>Data de evento</h2><div class="eventChecklistEventDateBlock">';
        for ($i = 0; $i < count($checklistDataObj->checklistJson->eventdate); $i++)
            writeItemHTML($checklistDataObj->checklistJson->eventdate[$i], $i, 'eventdate', $responsibleUsersList);
        echo '</div>';
    }

    if (isset($checklistDataObj->checklistJson->postevent))
    {
        echo '<h2>Pós-evento</h2><div class="eventChecklistPostEventBlock">';
        for ($i = 0; $i < count($checklistDataObj->checklistJson->postevent); $i++)
            writeItemHTML($checklistDataObj->checklistJson->postevent[$i], $i, 'postevent', $responsibleUsersList);
        echo '</div>';
    }
}
else
    echo '<p>Nenhum checklist definido.</p>';
?>