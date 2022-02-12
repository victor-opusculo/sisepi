
<?php if (!empty($_GET['messages'])) { ?>

<?php if (isset($_GET["receipt"]) && (bool)$_GET["receipt"]) { ?>
<script>
function openPopup(id)
{
	var url = '<?php echo URL\URLGenerator::generatePopupURL("libloanendreceipt", "id={id}"); ?>';
	var popup = window.open(url.replace('{id}', String(id)), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=800,height=800");
	popup.focus();
}
</script>
<div class="centControl">
	<button onclick="openPopup(<?php echo $bpubObj->id; ?>);">Gerar comprovante</button>
</div>
<?php } ?>

<?php } else if ($bpubObj !== null) { ?>

<form action="<?php echo URL\URLGenerator::generateFileURL("post/deletelibborrowedpub.post.php", "cont=libraryborrowedpubs&action=finalize&id=$bpubObj->id"); ?>" method="post">
	<?php $finalizeOnDate = date("Y-m-d H:i:s"); ?>
	<p style="text-align: center;">Deseja finalizar este empréstimo na data de hoje? Certifique-se de que a publicação está sob posse da biblioteca.</p>
	<div class="viewDataFrame">
		<input type="hidden" name="bpubId" value="<?php echo $bpubObj->id; ?>"/>
		<input type="hidden" name="finalizeOnDate" value="<?php echo $finalizeOnDate; ?>"/>
		<label>ID: </label><?php echo $bpubObj->id; ?> <br/>
		<label>Publicação emprestada: </label><?php echo hsc($bpubObj->title); ?> <br/>
		<label>Usuário: </label><?php echo hsc($bpubObj->userName); ?> <br/>
		<label>Data de empréstimo: </label><?php echo date_format(date_create($bpubObj->borrowDatetime), "d/m/Y H:i:s"); ?> <br/>
		<label>Data de devolução prevista: </label><?php echo date_format(date_create($bpubObj->expectedReturnDatetime), "d/m/Y H:i:s"); ?> <br/>
		<label>Data de devolução após finalizar: </label><?php echo date_format(date_create($finalizeOnDate), "d/m/Y H:i:s"); ?>
	</div>
	<div class="centControl">
		<input type="submit" name="btnsubmitFinalizeLoan" value="Sim, finalizar"/>
<input type="button" value="Não finalizar" onclick="window.location.href = '<?php echo URL\URLGenerator::generateSystemURL("libraryborrowedpubs"); ?>'"/>
	</div>
</form>

<?php } ?>