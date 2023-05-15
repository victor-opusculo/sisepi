
<?php if(isset($otpObj)): ?>

<div class="viewDataFrame">
    <label>ID: </label><?= $otpObj->id ?><br/>
    <label>Docente: </label><?= hsc($otpObj->getOtherProperties()->professorName) ?>(ID: <?= $otpObj->professorId ?>)<br/>
    <label>Data de expiração: </label><?= hsc(date_create($otpObj->expiryDateTime)->format('d/m/Y H:i:s')) ?>
</div>

<form method="post" action="<?= URL\URLGenerator::generateFileURL('post/professors3.editotp.post.php', [ 'cont' => 'professors', 'action' => 'view', 'id' => $otpObj->professorId ]) ?>">

    <span class="formField">
        <label>Nova senha: <input type="number" min="100000" max="999999" step="1" required name="professorsotps:txtNewPassword"/></label>
    </span>
    <span class="formField">
        <label>Nova data de expiração:
            <input type="date" name="dateExpiry" required value="<?= date_create($otpObj->expiryDateTime)->format('Y-m-d') ?>" />
            <input type="time" name="timeExpiry" step="1" required value="<?= date_create($otpObj->expiryDateTime)->format('H:i:s') ?>"/>
        </label>
    </span>

    <div class="centControl">
        <input type="hidden" name="professorsotps:hidOtpId" value="<?php echo $otpObj->id; ?>"/> 
		<input type="hidden" name="professorsotps:hidProfessorId" value="<?php echo $otpObj->professorId; ?>"/> 
        <input type="submit" value="Editar OTP" name="btnsubmitEditOtp" />
    </div>
</form>

<?php endif; ?>