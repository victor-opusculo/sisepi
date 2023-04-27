
<?php if (isset($odsRelation)): ?>
<form method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/odsrelations.delete.post.php", "cont=odsrelations&action=home"); ?>" >
    <p style="text-align: center;">Deseja realmente excluir esta relação ODS? Esta operação é irreversível!</p>
    
    <div class="viewDataFrame">
        <label>ID: </label><?= $odsRelation->id ?><br/> 
        <label>Nome: </label><?= hsc($odsRelation->name) ?><br/>
        <label>Exercício: </label><?= hsc($odsRelation->year) ?><br/>
        <label>Evento: </label>
        <?php if (!empty($odsRelation->eventId)): ?>
            <a href="<?= URL\URLGenerator::generateSystemURL('events', 'view', $odsRelation->eventId) ?>"><?= hsc($odsRelation->getOtherProperties()->eventName) ?></a><br/>
        <?php else: ?>
            <em>Nenhum</em><br/>
        <?php endif; ?>
    </div>
    
    <div class="centControl">
        <input type="submit" value="Sim, excluir" name="btnsubmitDelete" />
        <input type="button" value="Não excluir" onclick="history.back();" />
    </div>
    <input type="hidden" name="odsrelations:odsRelationId" value="<?php echo $odsRelation->id; ?>" />
</form>
<?php endif; ?>