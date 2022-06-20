<?php if (isset($surveyDataRowObj)): ?>
<form method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/events3.deletesinglesurvey.post.php", "cont=events3&action=deletesinglesurvey&id=" . $surveyDataRowObj->id); ?>" >
    <p style="text-align: center;">Deseja realmente excluir esta pesquisa de satisfação? Esta operação é irreversível!</p>
    
    <div class="viewDataFrame">
        <label>ID: </label><?php echo $surveyDataRowObj->id; ?> <br/>
        <label>Data de envio: </label><?php echo date_create($surveyDataRowObj->registrationDate)->format('d/m/Y H:i:s'); ?> <br/>
        <label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('events', 'view', $surveyDataRowObj->eventId); ?>"><?php echo $surveyDataRowObj->eventName; ?></a>
    </div>
    
    <div class="centControl">
        <input type="submit" value="Sim, excluir" name="btnsubmitDelete" />
        <input type="button" value="Não excluir" onclick="history.back();" />
    </div>
    <input type="hidden" name="surveyId" value="<?php echo $surveyDataRowObj->id; ?>" />
</form>
<?php endif; ?>