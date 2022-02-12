
<?php if ($bpubObj !== null) { ?>
<div class="viewDataFrame">
	<label>ID: </label><?php echo $bpubObj->id; ?> <br/>
	<label>Publicação emprestada: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("librarycollection", "view", $bpubObj->publicationId); ?>"><?php echo hsc($bpubObj->title); ?></a> <br/>
	<label>Usuário: </label><a href="<?php echo URL\URLGenerator::generateSystemURL("libraryusers", "view", $bpubObj->libUserId); ?>"><?php echo hsc($bpubObj->userName); ?></a> <br/>
	<label>Data de empréstimo: </label><?php echo date_format(date_create($bpubObj->borrowDatetime), "d/m/Y H:i:s"); ?> <br/>
	<label>Data de devolução prevista: </label><?php echo date_format(date_create($bpubObj->expectedReturnDatetime), "d/m/Y H:i:s"); ?> <br/>
	<label>Devolução efetuada em: </label>
		<?php 
			if ($bpubObj->returnDatetime)
				echo date_format(date_create($bpubObj->returnDatetime), "d/m/Y H:i:s"); 
			else
				echo '<span style="color: red;">Não devolvida ainda.</span>';
		?> <br/>
		
	<?php if ($bpubObj->returnDatetime): ?>
	<div class="centControl">
		<script>
			function openPopupFinalizeLoan(id)
			{
				var url = '<?php echo URL\URLGenerator::generatePopupURL("libloanendreceipt", "id={id}"); ?>';
				var popup = window.open(url.replace("{id}", String(id)), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=800,height=800");
				popup.focus();
			}
		</script>
		<button onclick="openPopupFinalizeLoan(<?php echo $bpubObj->id; ?>);">Gerar comprovante de devolução</button>
	</div>
	<?php else: ?>
	<div class="centControl">
		<script>
			function openPopupCreateLoan(id)
			{
				var url = '<?php echo URL\URLGenerator::generatePopupURL("libloancreatedreceipt", "id={id}"); ?>';
				var popup = window.open(url.replace("{id}", String(id)), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=800,height=800");
				popup.focus();
			}
		</script>
		<button onclick="openPopupCreateLoan(<?php echo $bpubObj->id; ?>);">Gerar comprovante de empréstimo</button>
	</div>
	<?php endif; ?>
</div>
<?php if (!$bpubObj->returnDatetime) { ?>
<div class="editDeleteButtonsFrame">
		<ul>
			<li><a id="linkDelete" href="<?php echo URL\URLGenerator::generateSystemURL("libraryborrowedpubs", "finalize", $bpubObj->id); ?>">Finalizar (fazer devolução)</a></li>
		</ul>
	</div>
<?php } ?>
<?php } ?>