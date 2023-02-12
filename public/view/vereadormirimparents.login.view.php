<?php if ($operation === "login"): ?>
    <form method="post" action="<?php echo URL\URLGenerator::generateSystemURL('vereadormirimparents', 'login'); ?>" onsubmit="document.getElementById('vmParentPanelLoginLoadingAnim').style.display = 'unset';">
        <span class="messageFrameWithIcon">
            <img class="messageFrameIcon" src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/infos.png'); ?>"/>
            Informe abaixo o seu e-mail de pai/responsável fornecido no ato de inscrição do Vereador Mirim. Será enviada uma mensagem com uma senha temporária para permitir o seu acesso ao Painel do Responsável. 
            O e-mail requer alguns segundos para ser enviado, clique no botão uma vez e aguarde.
        </span>
        <div class="centControl">    
            <span class="formField">
                <label>E-mail: <input type="email" required="required" size="40" name="vmParentEmail"/></label>
                <input style="vertical-align: middle;" type="submit" name="submitVmParentLogin" value="Prosseguir"/>
            </span>
            <span id="vmParentPanelLoginLoadingAnim" style="display:none;">
                <img src="<?php echo URL\URLGenerator::generateBaseDirFileURL('pics/loading.gif'); ?>" alt="Carregado, aguarde..."/>
            </span>
        </div>
    </form>
<?php elseif ($operation === "verifyotp"): ?>
    <form method="post" action="<?php echo URL\URLGenerator::generateSystemURL('vereadormirimparents', 'login'); ?>">
        <div class="centControl">    
            <span class="formField">
                <label>Insira a senha temporária enviada para o seu e-mail: <input type="text" size="20" pattern="[0-9]{6}" name="givenOTP"/></label>
                <input style="vertical-align: middle;" type="submit" name="submitVmParentOTP" value="Entrar" />
            </span>
            <?php if ($wrongOTP): ?>
                <p style="color: red;">Senha incorreta!</p>
            <?php endif; ?>
            <input type="hidden" name="otpId" value="<?php echo $otpId; ?>" />
        </div>
    </form>
<?php endif; ?>