
<?php function isAlreadySubscribed($availableNotification, array $subscriptionsList)
{
    foreach ($subscriptionsList as $sub)
        if ($sub->notMod === $availableNotification->module && (int)$sub->notId === (int)$availableNotification->id)
            return true;

    return false;
}
?>

<form method="post" action="<?= URL\URLGenerator::generateFileURL('post/notifications.subscribe.post.php', [ 'cont' => $_GET['cont'], 'action' => $_GET['action'] ]) ?>">
<p>Marque abaixo as notificações em que você deseja se inscrever e receber. Algumas permitem definir condições específicas para serem enviadas.
     Caso você deseje definir condições, salve as alterações clicando no botão "Alterar inscrições" antes de abrir a página de condições.</p>
<ol>
    <?php foreach ($notificationsAvailable as $avnot): ?>
        <li>
            <label>
                <input 
                        type="checkbox"
                        name="subscribedNotifications[]"
                        value="<?= $avnot->module . '_' . $avnot->id ?>"
                        <?= isAlreadySubscribed($avnot, $subscribed) ? ' checked ' : '' ?>
                        /> <?= hsc($avnot->name) ?>
            </label>
            <?php
            
            $ident = $notificationDefinitions[$avnot->module][(string)$avnot->id]['conditionsComponentName']; ?>
            <?php if ($ident !== null): ?>
                <span> (<a href="<?= URL\URLGenerator::generateSystemURL('notifications', 'setconditions', "{$avnot->module}_{$avnot->id}") ?>">Definir condições</a>)</span>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ol>
<div class="centControl">
    <input type="submit" name="btnsubmitSubmitNotificationSubscriptions" value="Alterar inscrições"/>
</div>
</form>