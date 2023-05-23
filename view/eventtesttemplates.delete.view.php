
<?php if (isset($templateDbEntity)): ?>
<form method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/eventtesttemplates.delete.post.php", "cont=eventtesttemplates&action=home"); ?>" >
    <p style="text-align: center;">Deseja realmente excluir este modelo de avaliação? Esta operação é irreversível!</p>
    
    <div class="viewDataFrame">
        <label>ID: </label><?php echo $templateDbEntity->id; ?><br/> 
        <label>Nome: </label><?php echo hsc($templateDbEntity->name); ?>
    </div>
    
    <div class="centControl">
        <input type="submit" value="Sim, excluir" name="btnsubmitDelete" />
        <input type="button" value="Não excluir" onclick="history.back();" />
    </div>
    <input type="hidden" name="eventtesttemplate:hidTestTemplateId" value="<?php echo $templateDbEntity->id; ?>" />
</form>
<?php endif; ?>