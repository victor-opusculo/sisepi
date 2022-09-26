<?php if (isset($subscriptionListInfos)): ?>

    <div class="viewDataFrame">
        <label>Evento: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "view", $subscriptionListInfos->eventId); ?>"><?php echo $subscriptionListInfos->name; ?></a>
    </div>
    <br/>
    <?php if ((bool)$subscriptionListInfos->subscriptionListNeeded): ?>
        <form action="<?php echo URL\URLGenerator::generateFileURL("post/events3.subscribe.post.php", "cont=events3&action=subscribe"); ?>" method="post">
            <span class="formField"><label>Nome: <input type="text" maxlengh="110" size="60" name="txtName" required="required"/></label></span>
            <span class="formField"><label>E-mail: <input type="email" maxlengh="110" size="60" name="txtEmail" required="required"/></label></span>
            <br/>
            <?php 
                require_once __DIR__ . '/../includes/GenerateView/EventSubscription.php';
                \GenerateView\EventSubscription\writeSubscriptionFormFieldsHTML($subscriptionTemplateObj, null, false, $connection); 
                $connection->close();
            ?>

            <input type="hidden" name="eventId" value="<?php echo $subscriptionListInfos->eventId; ?>" />
            <br/>
            <input type="submit" name="btnsubmitSubmit" value="Criar inscrito"></input>
        </form>
    <?php endif; ?>
<?php endif; ?>