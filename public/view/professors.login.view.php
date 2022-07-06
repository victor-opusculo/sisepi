<?php if ($operation === "login"): ?>
    <form method="post" action="<?php echo URL\URLGenerator::generateSystemURL('professors', 'login'); ?>">
        <div class="centControl">    
            <span class="formField">
                <label>E-mail cadastrado: <input type="email" size="40" name="professorEmail"/></label>
                <input style="vertical-align: middle;" type="submit" name="submitProfessorLogin" value="Prosseguir" />
            </span>
            
        </div>
    </form>
<?php elseif ($operation === "verifyotp"): ?>
    <form method="post" action="<?php echo URL\URLGenerator::generateSystemURL('professors', 'login'); ?>">
        <div class="centControl">    
            <span class="formField">
                <label>Insira a senha temporária enviada para o seu e-mail: <input type="text" size="20" pattern="[0-9]{6}" name="givenOTP"/></label>
                <input style="vertical-align: middle;" type="submit" name="submitProfessorOTP" value="Entrar" />
            </span>
            <?php if ($wrongOTP): ?>
                <p style="color: red;">Senha incorreta!</p>
            <?php endif; ?>
            <input type="hidden" name="otpId" value="<?php echo $otpId; ?>" />
        </div>
    </form>
<?php endif; ?>