<?php if (isset($subscriptionListInfos)): ?>

    <div class="viewDataFrame">
        <label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", $subscriptionListInfos->eventId); ?>"><?php echo $subscriptionListInfos->name; ?></a>
    </div>
    <br/>
    <?php if ((bool)$subscriptionListInfos->subscriptionListNeeded): ?>
        <form action="<?php echo URL\URLGenerator::generateFileURL("post/events3.subscribe.post.php", "cont=events3&action=subscribe"); ?>" method="post">
            <span class="formField"><label>Nome: <input type="text" maxlengh="110" size="60" name="txtName" required="required"/></label></span>
            <span class="formField"><label>Nome social (se houver): <input type="text" size="40" maxlengh="110" name="txtSocialName" /></label></span>
            <span class="formField"><label>E-mail: <input type="email" maxlengh="110" size="60" name="txtEmail" required="required"/></label></span>
            <br/>
            <span class="formField">A pessoa concorda com termo de consentimento para o tratamento de seus dados pessoais?
                <br/> 
                <label><input type="checkbox" name="chkAgreesWithConsentForm" value="1" /> Concorda</label>
            </span>
            <span class="formField">Termo: <a href="<?php echo $consentFormLink; ?>"><?php echo $consentFormLink; ?></a></span>
            <input type="hidden" name="hidConsentForm" value="<?php echo $consentFormLink; ?>" />
            <input type="hidden" name="eventId" value="<?php echo $subscriptionListInfos->eventId; ?>" />
            <br/>
            <input type="submit" name="btnsubmitSubmit" value="Criar inscrito"></input>
        </form>
    <?php endif; ?>
<?php endif; ?>