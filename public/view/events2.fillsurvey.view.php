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
    .surveyAnswerArea label { cursor: pointer; }

    h2
    {
        margin: 0.3em;
    }

</style>
<?php

function writeItemHTML($item, $index, $block)
{
    if (!isset($item->type)) $item->type = "yesNo";
    ?>
        <div class="surveyItem">
            <span class="surveyItemTitle"><?php echo hsc($item->title); ?></span>
            <span class="surveyAnswerArea">
                <?php switch ($item->type)
                {
                    case 'yesNo':
                        echo '<label><input type="radio" name="'. ("{$block}[{$index}]") . '" value="1" required="required" /> Sim</label><br/>';
                        echo '<label><input type="radio" name="'. ("{$block}[{$index}]") . '" value="0" required="required" /> Não</label>';
                        if ($item->optional) echo '<br/><label><input type="radio" name="'. ("{$block}[{$index}]") . '" value="" required="required" /> Não responder</label><br/>';
                        break;
                    case 'fiveStarRating':
                        echo '<label><input type="radio" name="'. ("{$block}[{$index}]") . '" value="1" required="required" /> 1 - Muito Ruim</label><br/>';
                        echo '<label><input type="radio" name="'. ("{$block}[{$index}]") . '" value="2" required="required" /> 2 - Ruim</label><br/>';
                        echo '<label><input type="radio" name="'. ("{$block}[{$index}]") . '" value="3" required="required" /> 3 - Regular</label><br/>';
                        echo '<label><input type="radio" name="'. ("{$block}[{$index}]") . '" value="4" required="required" /> 4 - Bom</label><br/>';
                        echo '<label><input type="radio" name="'. ("{$block}[{$index}]") . '" value="5" required="required" /> 5 - Excelente</label>';
                        if ($item->optional) echo '<br/><label><input type="radio" name="'. ("{$block}[{$index}]") . '" value="" required="required" /> Não responder</label><br/>';
                        break;
                    case 'checkList':
                        foreach ($item->checkList as $checkIndex => $checkItem)
                            echo '<label><input type="checkbox" name="' . ("{$block}[{$index}][{$checkIndex}]") . '" value="1" />' . hsc($checkItem->name) . '</label><br/>';
                        break;
                    case 'shortText':
                        echo '<input type="text" name="' . ("{$block}[{$index}]") . '" size="40" maxlength="255" ' . ($item->optional ? '' : 'required="required"') . ' />';
                        break;
                    case 'textArea':
                        echo '<textarea rows="4" style="width: 100%;" name="' . ("{$block}[{$index}]") . '" ' . ($item->optional ? '' : 'required="required"') . '></textarea>';
                        break;
                }
                ?>
            </span>
        </div>
    <?php
}

?>

<?php if ((empty($_GET['filled']) || (bool)$_GET['filled'] == false) && isset($eventInfos)) { ?>

<div class="viewDataFrame">
    <label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('events', 'view', $eventInfos->id); ?>"><?php echo hsc($eventInfos->name); ?></a> <br/>
    <label>Tipo: </label><?php echo hsc($eventInfos->getOtherProperties()->typeName); ?> <br/>
    <label>Início: </label><?php echo (new DateTime($eventInfos->getOtherProperties()->beginDate))->format("d/m/Y"); ?> <br/>
    <label>Encerramento: </label><?php echo (new DateTime($eventInfos->getOtherProperties()->endDate))->format("d/m/Y"); ?> <br/>
</div>

<?php if (date_create(date('Y-m-d')) >= date_create($eventInfos->getOtherProperties()->endDate)) { ?> 
    <?php if (empty($studentInfos)) { ?>
        <form method="get">
            <p>Insira abaixo o seu e-mail usado na inscrição ou listas de presença. Não se preocupe com a sua identificação, pois ela só é realizada para garantir que apenas pessoas
                inscritas e aprovadas respondam a pesquisa. A leitura, análise e relatórios das respostas são feitos sem a identificação.</p> 
            <?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
                <input type="hidden" name="cont" value="events2" />
                <input type="hidden" name="action" value="fillsurvey" />
            <?php endif; ?>
            <input type="hidden" name="eventId" value="<?php echo $eventInfos->id; ?>" />
            <label>E-mail: <input name="email" type="email" size="40"/></label>
            <input type="submit" value="Entrar" />
        </form>
    <?php } else { ?>
        <form method="post" action="<?php echo URL\URLGenerator::generateFileURL('post/events2.fillsurvey.post.php', 
        [ 'cont' => 'events2', 'action' => 'fillsurvey', 'eventId' => $eventInfos->id, 'email' => $studentInfos->email, 'goTo' => $_GET['goTo'] ?? '' ]); ?>"> 
            <?php 

            if (isset($surveyObject->head))
            {
                echo '<div class="surveyHeadBlock">';
                echo '<h2>Participante</h2>';
                foreach ($surveyObject->head as $index => $item)
                    writeItemHTML($item, $index, 'head');
                echo '</div>';
            }

            if (isset($surveyObject->body))
            {
                echo '<div class="surveyBodyBlock">';
                echo '<h2>Avaliação do Evento</h2>';
                foreach ($surveyObject->body as $index => $item)
                    writeItemHTML($item, $index, 'body');
                echo '</div>';
            }

            if (isset($surveyObject->foot))
            {
                echo '<div class="surveyFootBlock">';
                echo '<h2>Melhorias e Sugestões</h2>';
                foreach ($surveyObject->foot as $index => $item)
                    writeItemHTML($item, $index, 'foot');
                echo '</div>';
            }

            ?>
            <input type="hidden" name="eventId" value="<?php echo $eventInfos->id; ?>" />
            <input type="hidden" name="studentEmail" value="<?php echo $studentInfos->email; ?>" />
            <div class="centControl">
                <input type="submit" name="btnsubmitSubmitSurvey" value="Enviar" />
            </div>
        </form>
    <?php } ?> 
<?php } else { ?>
    <p class="centControl">Este evento ainda não terminou.</p>
<?php } 
} else if (isset($eventInfos) && isset($studentInfos) && !empty($_GET['filled']) && (bool)$_GET['filled']) { ?>
<div class="centControl">
    <?php 
        require_once "controller/component/GoToButton.class.php";
        (new GoToButton(['actions' => $_GET['goTo'] ?? '', 'queryString' => [ 'eventId' => $eventInfos->id, 'email' => $studentInfos->email ] ]))->render();
    ?>
</div>
<?php } ?>