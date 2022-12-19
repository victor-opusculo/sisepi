
<form method="post" action="<?= URL\URLGenerator::generateFileURL('post/vereadormirimlegislatures.create.post.php', [ 'title' => $this->subtitle ] ) ?>">

    <span class="formField">
        <label>Nome desta legislatura: <input type="text" maxlength="255" size="40" name="vmlegislatures:txtName" required/></label>
    </span>
    <span class="formField">
        <label>Data de início: <input type="date" name="vmlegislatures:dateBegin" required/></label>
    </span>  
    <span class="formField">
        <label>Data de fim: <input type="date" name="vmlegislatures:dateEnd" required/></label>
    </span>  

    <input type="submit" name="btnsubmitCreateLegislature" value="Criar legislatura" />
</form>