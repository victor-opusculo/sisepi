<?php
if ($profObject !== null)
{
?>
<form action="<?php echo URL\URLGenerator::generateFileURL("post/deleteprofessor.post.php", "cont=professors&action=delete&id=$profObject->id"); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir este docente? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
		<label>Nome: </label><?php echo hsc($profObject->name); ?><br/>
		<label>Data de registro: </label><?php echo date_format(date_create($profObject->registrationDate), "d/m/Y H:i:s") ?><br/><br/>
		<input type="hidden" name="profId" value="<?php echo $profObject->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteProfessor" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="window.location.href = '<?php echo URL\URLGenerator::generateSystemURL("professors"); ?>';"/>
	</div>
	
</form>

<?php
}
?>