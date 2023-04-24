
<?php if (isset($entryObj)): ?>
<form method="post" action="<?php echo URL\URLGenerator::generateFileURL("post/budget.delete.post.php", "cont=budget&action=home"); ?>" >
    <p style="text-align: center;">Deseja realmente excluir esta dotação? Esta operação é irreversível!</p>
    
    <div class="viewDataFrame">
        <label>ID: </label><?= $entryObj->id ?><br/> 
        <label>Data prevista: </label><?= date_create($entryObj->date)->format('d/m/Y') ?><br/>
        <label>Tipo: </label><?= $entryObj->value >= 0 ? 'Receita' : 'Despesa' ?><br/>
        <label>Categoria: </label><?= hsc($entryObj->getOtherProperties()->categoryName) ?><br/>
        <label>Detalhes: </label><?= hsc($entryObj->details) ?><br/>
        <label>Valor: </label><span style="<?= $entryObj->value >= 0 ? 'color:green;' : 'color:red;' ?>"><?= hsc(formatDecimalToCurrency($entryObj->getOtherProperties()->absValue)) ?></span><br/>
    </div>
    
    <div class="centControl">
        <input type="submit" value="Sim, excluir" name="btnsubmitDelete" />
        <input type="button" value="Não excluir" onclick="history.back();" />
    </div>
    <input type="hidden" name="budgetentries:budgetEntryId" value="<?php echo $entryObj->id; ?>" />
</form>
<?php endif; ?>