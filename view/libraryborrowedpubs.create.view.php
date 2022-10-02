
<?php if (!empty($_GET['messages'])) { ?>

<?php if (isset($_GET["newId"]) && isId($_GET["newId"])) { ?>
<script>
function openPopup(id)
{
	var url = '<?php echo URL\URLGenerator::generatePopupURL("libloancreatedreceipt", "id={id}" ); ?>';
	var popup = window.open(url.replace('{id}', String(id)), "popup", "toolbar=1,scrollbars=1,location=1,statusbar=no,menubar=1,width=800,height=800");
	popup.focus();
}
</script>
<div class="centControl">
	<button onclick="openPopup(<?php echo $_GET["newId"]; ?>);">Gerar comprovante</button>
</div>
<?php } ?>
<?php } else { ?>

<script>
	const fileBasePath = '<?php echo URL\URLGenerator::generateFileURL(); ?>';
	const popupURL = '<?php echo URL\URLGenerator::generatePopupURL("{popup}"); ?>';
</script>
<script src="<?php echo URL\URLGenerator::generateFileURL('view/libraryborrowedpubs.create.view.js'); ?>"></script>

<form id="frmCreateLoan" action="<?php echo URL\URLGenerator::generateFileURL('post/createlibborrowedpub.post.php', 'cont=libraryborrowedpubs&action=create'); ?>" method="post">
<div style="display: flex;">
	<div style="flex: 50%;">
		<div class="centControl">
			<strong>Publicação a ser emprestada</strong><br/>
			<span style="display: flex; align-items: center; justify-content: center;">
				<label>ID: <input type="number" name="numPubId" min="1" required="required" style="width: 100px;"/></label>
				<button type="button" id="btnLoadPublication" style="min-width:20px;" ><?php echo htmlspecialchars(">"); ?></button>
				<button type="button" id="btnSearchPublication"><img src="<?php echo URL\URLGenerator::generateFileURL("pics/search.png"); ?>" alt="pesquisar"/> Procurar</button>
			</span>
		</div>
		<div class="viewDataFrame">
			<label>Título e subtítulo: </label><span id="pubTitle"></span> <br/>
			<label>Autor: </label><span id="pubAuthor"></span> <br/>
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
<div id="nextReservationsFrame" style="display: none;">
	<h3>Próximas reservas (válidas e não atendidas)</h3>
	<table>
		<thead>
			<tr>
				<th>ID do usuário</th><th>Usuário</th><th>Data da reserva</th><th>Selecionar</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<br/>
<div class="centControl">
	<?php $borrowDatetime = date("Y-m-d H:i:s"); ?>
	
	<input type="hidden" name="hidBorrowDatetime" value="<?php echo $borrowDatetime; ?>"/>
	<span>Data e hora do empréstimo: <?php echo date_format(date_create($borrowDatetime), "d/m/Y H:i:s"); ?></span><br/>
	<span>Data e hora limite para a devolução: <input type="date" name="dateReturnDate" required="required"/><input type="time" name="timeReturnTime" step="1" required="required"/></span><br/><br/>
	<input type="submit" name="btnsubmitCreateBPub" value="Emprestar" /> <br/><br/>
	<label><input type="checkbox" name="chkSkipReservations" value="1"/>Abrir exceção: Permitir este empréstimo mesmo havendo reservas válidas não atendidas</label>
</div>
</form>

<?php } ?>