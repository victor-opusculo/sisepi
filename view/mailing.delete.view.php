<?php
if (!empty($_GET["messages"]))
{ ?>

<?php
}
else
{
?>
<form action="<?php echo URL\URLGenerator::generateFileURL("post/deletemailingsubs.post.php", "cont=mailing&action=delete&id=$mailObject->id"); ?>" method="post">
	<p style="text-align: center;">Deseja realmente excluir este e-mail do mailing? Esta operação é irreversível!</p>
	<div class="viewDataFrame">
		<label>Nome: </label><?php echo hsc($mailObject->name); ?><br/>
		<label>E-mail: </label><?php echo hsc($mailObject->email); ?> <br/><br/>
		
		<label>Inscrito no evento: </label><?php echo hsc($mailObject->eventName); ?> <br/>
		<br/>
		<input type="hidden" name="mailId" value="<?php echo $mailObject->id; ?>"/> 
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitDeleteEmail" value="Sim, excluir"/>
		<input type="button" value="Não excluir" onclick="window.location.href = '<?php echo URL\URLGenerator::generateSystemURL("mailing"); ?>';"/>
	</div>
	
</form>

<?php
}
?>