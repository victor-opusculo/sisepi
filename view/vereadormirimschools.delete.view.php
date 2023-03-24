<?php if (!empty($schoolObj)): ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/vereadormirimschools.delete.post.php", [ 'cont' => $_GET['cont'], 'action' => 'home' ]); ?>" method="post">

	<p style="text-align: center;">Deseja realmente excluir esta escola de vereador mirim? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
        <label>ID: </label><?= $schoolObj->id ?><br/>
		<label>Nome: </label><?php echo hsc($schoolObj->name); ?><br/>
		<label>E-mail: </label><?php echo hsc($schoolObj->email) ?><br/>
		<label>Diretor(a): </label><?php echo hsc($schoolObj->directorName) ?><br/>
		<label>Data de registro: </label><?php echo hsc(date_create($schoolObj->registrationDate)->format('d/m/Y H:i:s')) ?><br/>
		<input type="hidden" name="vmschools:schoolId" value="<?php echo $schoolObj->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteVmSchool" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="history.back();"/>
	</div>
</form>

<?php endif; ?>