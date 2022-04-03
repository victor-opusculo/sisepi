
<form method="get">
    <?php if (URL\URLGenerator::useFriendlyURL === false): ?>
        <input type="hidden" name="cont" value="calendar"/>
    <?php endif; ?>
    <span class="searchFormField">
        <label>Mês: 
            <select name="month">
                <?php foreach ($monthsList as $num => $monthName): ?>
                    <option value="<?php echo $num + 1; ?>" <?php echo $mcalComp->getReferenceDateTime()->format("m") == $num + 1 ? 'selected="selected"' : ''; ?>><?php echo $monthName; ?></option>
                <?php endforeach; ?>
            </select>
        </label> 
        <label>Ano:
            <input type="number" name="year" value="<?php echo $mcalComp->getReferenceDateTime()->format("Y"); ?>" step="1" min="2020" max="5000" />
        </label>
        <button class="searchButton" type="submit"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="Abrir" title="Abrir"/></button>
        <a class="linkButton" href="<?php echo URL\URLGenerator::generateSystemURL("calendar", "createdate"); ?>">Novo data/evento</a>
    </span>
</form>

<?php $mcalComp->render(); ?>