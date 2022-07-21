<form method="get">
    <?php if (URL\URLGenerator::$useFriendlyURL === false): ?>
        <input type="hidden" name="cont" value="calendar"/>
        <input type="hidden" name="action" value="viewday"/>
    <?php endif; ?>
    <span class="searchFormField">
        <label>Ver dia: 
            <input type="date" name="day" value="<?php echo $dcalComp->getDateTime()->format("Y-m-d"); ?>" />
        </label>
        <button class="searchButton" type="submit"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="Abrir" title="Abrir"/></button>
        <a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("calendar", "createdate", null, [ 'day' => $dcalComp->getDateTime()->format("Y-m-d")] ); ?>">Novo data/evento</a>
    </span>
</form>


<?php $dcalComp->render(); ?>

<div class="rightControl">
    <a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("calendar", "viewweek", null, [ 'day' => $dcalComp->getDateTime()->format('Y-m-d') ]); ?>">Ver semana deste dia</a>
</div>