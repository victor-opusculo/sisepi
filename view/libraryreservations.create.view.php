
<?php if (!empty($_GET['messages'])) { ?>

<?php } else { ?>

<script>
	const fileBasePath = '<?php echo URL\URLGenerator::generateFileURL(); ?>';
	const popupURL = '<?php echo URL\URLGenerator::generatePopupURL("{popup}"); ?>';
</script>
<script src="<?php echo URL\URLGenerator::generateFileURL('view/libraryreservations.create.view.js'); ?>"></script>

<form id="frmCreateReservation" action="<?php echo URL\URLGenerator::generateFileURL('post/createlibreservation.post.php', 'cont=libraryreservations&action=create'); ?>" method="post">
<div style="display: flex;">
	<div style="flex: 50%;">
		<div class="centControl">
			<strong>Publicação a ser reservada</strong><br/>
			<span style="display: flex; align-items: center; justify-content: center;">
				<label>ID: <input type="number" name="numPubId" min="1" required="required" style="width: 100px;"/></label>
				<button type="button" id="btnLoadPublication" style="min-width:20px;" ><?php echo htmlspecialchars(">"); ?></button>
				<button type="button" id="btnSearchPublication"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
			</span>
		</div>
		<div class="viewDataFrame">
			<label>Título e subtítulo: </label><span id="pubTitle"></span> <br/>
			<label>Autor: </label><span id="pubAuthor"></span> <br/>
			<label>Categoria de acervo: </label><span id="pubColTypeName"></span> <br/>
			<label>Editora/Edição: </label><span id="pubPublisher_edition"></span> <br/>
			<label>Volume: </label><span id="pubVolume"></span> <br/>
			<label>Exemplar: </label><span id="pubCopyNumber"></span> <br/>
			<label>Disponível para empréstimo? </label><span id="pubIsAvailable"></span> <br/>
		</div>
	</div>
	<div style="flex: 50%;">
		<div class="centControl">
			<strong>Usuário</strong><br/>
			<span style="display: flex; align-items: center; justify-content: center;">
				<label>ID: <input type="number" name="numUserId" min="1" required="required" style="width: 100px;"/></label>
				<button type="button" id="btnLoadUser" style="min-width:20px;"><?php echo htmlspecialchars(">"); ?></button>
				<button type="button" id="btnSearchUser"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
			</span>
		</div>
		<div class="viewDataFrame">
			<label>Nome: </label><span id="userName"></span> <br/>
			<label>E-mail: </label><span id="userEmail"></span> <br/>
			<label>Telefone: </label><span id="userTelephone"></span> <br/>
			<label>Tipo: </label><span id="userTypeName"></span> <br/>
		</div>
	</div>
</div>

<br/>
<div class="centControl">
	<?php $reservationDatetime = date("Y-m-d H:i:s"); ?>
	
	<input type="hidden" name="hidReservationDatetime" value="<?php echo $reservationDatetime; ?>"/>
	<span>Data e hora da reserva: <?php echo date_format(date_create($reservationDatetime), "d/m/Y H:i:s"); ?></span><br/><br/>
	<input type="submit" name="btnsubmitCreateReservation" value="Reservar" /> <br/><br/>
</div>
</form>

<?php } ?>