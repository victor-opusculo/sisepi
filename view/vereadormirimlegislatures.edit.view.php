<?php if (!empty($legislatureObj)): ?>

<form method="post" action="<?= URL\URLGenerator::generateFileURL('post/vereadormirimlegislatures.edit.post.php', [ 'cont' => $_GET['cont'], 'action' => $_GET['action'], 'id' => $_GET['id'] ] ) ?>">

    <span class="formField">
        <label>Nome desta legislatura: <input type="text" maxlength="255" size="40" name="vmlegislatures:txtName" required value="<?= hscq($legislatureObj->name) ?>"/></label>
    </span>
    <span class="formField">
        <label>Data de início: <input type="date" name="vmlegislatures:dateBegin" required value="<?= hscq($legislatureObj->begin) ?>"/></label>
    </span>  
    <span class="formField">
        <label>Data de fim: <input type="date" name="vmlegislatures:dateEnd" required value="<?= hscq($legislatureObj->end) ?>"/></label>
    </span>  

    <input type="hidden" name="vmlegislatures:vmLegislatureId" value="<?= $legislatureObj->id ?>" />
    <input type="submit" name="btnsubmitEditLegislature" value="Editar legislatura" />
</form>

<?php endif; ?>