
<?php if (isset($templateObj)): ?>
<form method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/eventsurveytemplates.delete.post.php", "cont=eventsurveytemplates&action=delete&id=" . $templateObj->id); ?>" >
    <p style="text-align: center;">Deseja realmente excluir este modelo de pesquisa de satisfação? Esta operação é irreversível!</p>
    
    <div class="viewDataFrame">
        <label>ID: </label><?php echo $templateObj->id; ?><br/> 
        <label>Nome: </label><?php echo $templateObj->name; ?>
    </div>
    
    <div class="centControl">
        <input type="submit" value="Sim, excluir" name="btnsubmitDelete" />
        <input type="button" value="Não excluir" onclick="history.back();" />
    </div>
    <input type="hidden" name="surveyTemplateId" value="<?php echo $templateObj->id; ?>" />
</form>
<?php endif; ?>