<style>
    .surveyHeadBlock, .surveyBodyBlock, .surveyFootBlock { background-color: #eee; margin-bottom: 0.5em; border-radius: 10px; }
    .surveyHeadBlock, .surveyBodyBlock, .surveyFootBlock { padding: 0.5em;  }
    .surveyItem { padding: 0.3em; border-bottom: 1px solid darkgray; }

    .surveyBodyBlock { counter-reset: bodyitemcount; }
    .surveyBodyBlock .surveyItem > .surveyItemTitle::before { counter-increment: bodyitemcount; content: counter(bodyitemcount) ". "; }

    .surveyItemTitle
    {
        display: block;
        font-size: 102%;
        font-weight: bold;
    }

    .surveyAnswerArea { display: block; }

    h2 { margin: 0.3em; }

</style>

<?php 
function writeItems($surveyClassObj, $blockName)
{
     foreach ($surveyClassObj->itemsFromBlockAsObject($blockName) as $item): ?>
            <div class="surveyItem">
                <span class="surveyItemTitle"><?php echo hsc($item->title); ?></span>
                <span class="surveyAnswerArea"><?php echo nl2br(hsc($item->formattedAnswer)); ?></span>
            </div>
    <?php endforeach;
}
?>

<?php if (isset($surveyObj, $surveyDataRowObj)): ?>
    <div class="viewDataFrame">
        <label>ID: </label><?php echo $surveyDataRowObj->id; ?> <br/>
        <label>Data de envio: </label><?php echo date_create($surveyDataRowObj->registrationDate)->format('d/m/Y H:i:s'); ?> <br/>
        <label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('events', 'view', $surveyDataRowObj->eventId); ?>"><?php echo $surveyDataRowObj->eventName; ?></a>
    </div>

    <?php if ($surveyObj->hasBlock('head')): ?>
    <div class="surveyHeadBlock">
        <h2>Participante</h2>
        <?php writeItems($surveyObj, 'head'); ?>
    </div>
    <?php endif; ?>

    <?php if ($surveyObj->hasBlock('body')): ?>
    <div class="surveyBodyBlock">
        <h2>Avaliação do Evento</h2>
        <?php writeItems($surveyObj, 'body'); ?>
    </div>
    <?php endif; ?>

    <?php if ($surveyObj->hasBlock('foot')): ?>
    <div class="surveyFootBlock">
        <h2>Melhorias e Sugestões</h2>
        <?php writeItems($surveyObj, 'foot'); ?>
    </div>
    <?php endif; ?>

    <div class="editDeleteButtonsFrame">
		<ul>
			<li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("events3", "deletesinglesurvey", $surveyDataRowObj->id); ?>">Excluir</a></li>
		</ul>
	</div>

<?php endif; ?>

