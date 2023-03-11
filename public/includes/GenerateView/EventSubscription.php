<?php
//Public
namespace GenerateView\EventSubscription;

use mysqli;

require_once __DIR__ . '/../common.php';
require_once __DIR__ . '/../URL/URLGenerator.php';
require_once __DIR__ . '/../../model/database/enums.settings.database.php';

const IGNORE_REQUIRED_FIELDS = false;

function writeSubscriptionFormFieldsHTML(object $subscriptionTemplate, ?array $injectHtmlCallbacks = null, bool $writeOnlyTermsSection = false, ?mysqli $optConnection = null)
{
    $conn = $optConnection ?? createConnectionAsEditor();
    echo '<h3>Termos</h3>';

    if (!empty($injectHtmlCallbacks['preTerms']))
        foreach ($injectHtmlCallbacks['preTerms'] as $func)
            $func();

    foreach ($subscriptionTemplate->terms as $ti => $term)
    { ?>
        <span class="formField">
            <label><?= $term->titleLabel ?></label> (<a href="<?= \URL\URLGenerator::generateBaseDirFileURL("uploads/terms/{$term->termId}.pdf"); ?>">Leia aqui</a>)<br/>
            <label><input type="checkbox" name="terms[<?= $ti ?>]" value="1" <?php echo $term->required ? ' required ' : ''; ?>/> <?= $term->checkBoxLabel ?></label>
        </span>
      <?php  
    }

    if (!$writeOnlyTermsSection)
    {
        echo '<h3>Inscrição</h3>';

        if (!empty($injectHtmlCallbacks['preQuestions']))
            foreach ($injectHtmlCallbacks['preQuestions'] as $func)
                $func();

        foreach ($subscriptionTemplate->questions as $qi => $quest)
        {
            if (!empty($quest->hide))
            { ?>
                <label><input type="checkbox" id="chk_subsQuest_<?= $qi ?>" onchange="
                    document.getElementById('subsQuest_<?= $qi ?>').style.display = this.checked ? 'block' : 'none';
                    document.getElementById('subsQuest_<?= $qi ?>').querySelectorAll('input,select').forEach( inp => inp.disabled = !this.checked ); "/> <?= $quest->hide->checkBoxLabel ?></label>
                <span class="formField" id="subsQuest_<?= $qi ?>" style="display: none;" data-collapsible="1">
                    <span style="color:red;"><?= $quest->hide->infoText ?? '' ?></span><br/>
            <?php
            }
            else
            { ?>
                <span class="formField" id="subsQuest_<?= $qi ?>">
            <?php
            }

            ?> <input type="hidden" name="questIdentifier[<?= $qi ?>]" value="<?= hscq($quest->identifier) ?>" /> <?php

            switch ($quest->formInput->type)
            {
                case 'info':
                    writeInfoLabel($qi, $quest->formInput);
                    break;
                case 'text':
                    writeTextInput($qi, $quest->formInput, $quest->identifier);
                    break;
                case 'date':
                    writeDateInput($qi, $quest->formInput, $quest->identifier);
                    break;
                case 'radiobuttons':
                    writeRadioInput($qi, $quest->formInput, $quest->identifier, $conn);
                    break;
                case 'combobox':
                    writeComboInput($qi, $quest->formInput, $quest->identifier, $conn);
                    break;
            }
            ?>
                </span>
            <?php

        }
    }

    if (!$optConnection) $conn->close();
}

function writeInfoLabel(int $questId, object $formFieldObj)
{
    ?>
    <span <?php echo writeFieldProperties($formFieldObj->properties ?? new class{}); ?>><?= hsc($formFieldObj->label) ?><input type="hidden" name="questions[<?= $questId ?>]" value=""/></span>
    <?php
}

function writeTextInput(int $questId, object $formFieldObj, string $identifier)
{
    ?>
    <label><?= hsc($formFieldObj->label) ?> <input type="text" name="questions[<?= $questId ?>]" data-identifier="<?= $identifier ?>" <?php echo writeFieldProperties($formFieldObj->properties); ?> /></label>
    <?php
}

function writeDateInput(int $questId, object $formFieldObj, string $identifier)
{
    ?>
    <label><?= hsc($formFieldObj->label) ?> <input type="date" name="questions[<?= $questId ?>]" data-identifier="<?= $identifier ?>" <?php echo writeFieldProperties($formFieldObj->properties); ?> /></label>
    <?php
}

function writeRadioInput(int $questId, object $formFieldObj, string $identifier, ?mysqli $conn)
{
    ?>
    <span style="font-weight: bold;"><?= $formFieldObj->label ?></span><br/>
    <?php
    $options = [];
    if (is_object($formFieldObj->options))
    {
        if (!empty($formFieldObj->options->useDbEnum))
        {
            $enumOpts = getEnum($formFieldObj->options->useDbEnum, $conn);
            foreach ($enumOpts as $dr)
                $options[] = $dr['name'];
        }
    }
    else if (is_array($formFieldObj->options))
    {
        $options = $formFieldObj->options;
    }

    foreach ($options as $opt): ?>
        <label><input type="radio" name="questions[<?= $questId ?>]" value="<?= $opt ?>" data-identifier="<?= $identifier ?>" <?php echo writeFieldProperties($formFieldObj->properties); ?> /> <?= hsc($opt) ?></label> <br/>
    <?php endforeach;
}

function writeComboInput(int $questId, object $formFieldObj, string $identifier, ?mysqli $conn)
{
    $options = [];
    if (is_object($formFieldObj->options))
    {
        if (!empty($formFieldObj->options->useDbEnum))
        {
            $enumOpts = getEnum($formFieldObj->options->useDbEnum, $conn);
            foreach ($enumOpts as $dr)
                $options[] = $dr['name'];
        }
    }
    else if (is_array($formFieldObj->options))
    {
        $options = $formFieldObj->options;
    }

    ?> 
    <label><?= $formFieldObj->label ?>
        <select name="questions[<?= $questId ?>]" data-identifier="<?= $identifier ?>" <?php echo writeFieldProperties($formFieldObj->properties); ?> >
        <?php foreach ($options as $opt): ?>
            <option value="<?= hscq($opt) ?>"><?= hsc($opt) ?></option>
        <?php endforeach; ?>
        </select>
    </label>
    <?php
}

function writeFieldProperties(object $props)
{
    foreach ($props as $prop => $value)
    {
        if (strtolower($prop) === 'required' && IGNORE_REQUIRED_FIELDS)
            continue;
        
        echo " $prop=\"$value\" ";
    }
}