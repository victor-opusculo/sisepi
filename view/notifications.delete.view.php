<?php if (isset($notificationObj)): ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/notifications.delete.post.php", [ 'cont' => $_GET['cont'], 'action' => 'home' ]); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir esta notificação? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
        <div class="centControl">
            <img src="<?= URL\URLGenerator::generateFileURL($notificationObj->iconFilePath) ?>" width="64" />
        </div>
        <label>ID: </label><?= $notificationObj->id ?> <br/>
		<label>Título: </label><?php echo hsc($notificationObj->title); ?><br/>
		<label>Corpo: </label><?php echo hsc($notificationObj->description); ?><br/>
		<label>Data e hora: </label><?php echo date_format(date_create($notificationObj->dateTime), "d/m/Y H:i:s") ?><br/><br/>
		<input type="hidden" name="notId" value="<?php echo $notificationObj->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteNotification" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="history.back();"/>
	</div>
	
</form>

<?php endif; ?>