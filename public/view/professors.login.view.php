<?php if ($operation === "login"): ?>
    <form method="post" action="<?php echo URL\URLGenerator::generateSystemURL('professors', 'login'); ?>" onsubmit="document.getElementById('professorPanelLoginLoadingAnim').style.display = 'unset';">
        <span class="messageFrameWithIcon">
            <img class="messageFrameIcon" src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/infos.png'); ?>"/>
            Informe abaixo o seu e-mail usado no formulário de cadastro. Será enviada uma mensagem com uma senha temporária para permitir o seu acesso ao Painel de Docente. 
            O e-mail requer alguns segundos para ser enviado, clique no botão uma vez e aguarde.
        </span>
        <div class="centControl">    
            <span class="formField">
                <label>E-mail: <input type="email" required="required" size="40" name="professorEmail"/></label>
                <input style="vertical-align: middle;" type="submit" name="submitProfessorLogin" value="Prosseguir"/>
            </span>
            <span id="professorPanelLoginLoadingAnim" style="display:none;">
                <img src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/loading.gif'); ?>" alt="Carregado, aguarde..."/>
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