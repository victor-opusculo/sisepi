<?php if (isset($testObj)): ?>
<form method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/events4.deletesingletest.post.php", "cont=events&action=view&id=" . $testObj->eventId); ?>" >
    <p style="text-align: center;">Deseja realmente excluir esta avaliação preenchida? Esta operação é irreversível!</p>
    
    <div class="viewDataFrame">
        <label>ID: </label><?php echo $testObj->id; ?> <br/>
        <label>Data de envio: </label><?php echo date_create($testObj->sentDateTime)->format('d/m/Y H:i:s'); ?> <br/>
        <label>Participante: </label><?= hsc($testObj->studentName) ?><br/>
        <label>Resultado: </label><?= $testObj->isApproved()[0] ? 'Aprovado' : 'Reprovado' ?><br/>
        <label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL('events', 'view', $testObj->eventId); ?>"><?php echo hsc($testObj->getOtherProperties()->eventName); ?></a>
    </div>
    
    <div class="centControl">
        <input type="submit" value="Sim, excluir" name="btnsubmitDelete" />
        <input type="button" value="Não excluir" onclick="history.back();" />
    </div>
    <input type="hidden" name="testId" value="<?php echo $testObj->id; ?>" />
</form>
<?php endif; ?>