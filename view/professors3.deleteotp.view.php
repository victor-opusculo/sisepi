<?php if (isset($otpObj)): ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/professors3.deleteotp.post.php", [ 'cont' => 'professors', 'action' => 'view', 'id' => $otpObj->professorId ]); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir esta senha temporária? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
        <label>ID: </label><?= $otpObj->id ?><br/>
		<label>Docente: </label><?php echo hsc($otpObj->getOtherProperties()->professorName); ?><br/>
		<label>Data de expiração: </label><?php echo hsc(date_create($otpObj->expiryDateTime)->format('d/m/Y H:i:s')) ?><br/>

		<input type="hidden" name="professorsotps:hidOtpId" value="<?php echo $otpObj->id; ?>"/> 
		<input type="hidden" name="professorsotps:hidProfessorId" value="<?php echo $otpObj->professorId; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteOtp" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="history.back();"/>
	</div>
	
</form>

<?php endif; ?>