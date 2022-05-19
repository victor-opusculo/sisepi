<?php
require_once("checkLogin.php");
require_once("../includes/common.php");
require_once("../model/database/artmuseum.database.php");

$fullData = getFullArtPieceList( ($_GET["orderBy"] ?? ""), ($_GET["q"] ?? "") );
if (!$fullData) die("Não há dados de acordo com o critério atual de pesquisa.");

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Acervo do museu</title>
	<link rel="shortcut icon" type="image/x-icon" href="../pics/favicon.ico">
	<style>
		.artPiecePage
		{
			display: flex;
			height: 450px;
			border: 1px solid black;
			padding: 0.5cm;
			font-size: 1.1em;
			margin-bottom: 1cm;
		}

		.infoBox, .picBox
		{
			flex: 50%;
		}

		.picBox { text-align: center; }

		.picBox img
		{
			max-width: 100%;
			max-height: 100%;
		}

		@media print 
		{
			.artPiecePage 
			{ 
				page-break-inside: avoid;
				/*page-break-after: always;*/
			} 
		}
	</style>
</head>
	<body>
		<?php foreach ($fullData as $ap): ?>
		<div class="artPiecePage">
			<div class="infoBox">
				<p><strong>ID: </strong><?php echo $ap['id']; ?></p>
				<p><strong>Número de patrimônio (CMI): </strong><?php echo $ap['CMI_propertyNumber']; ?></p>
				<p><strong>Nome da obra: </strong><?php echo $ap['name']; ?></p>
				<p><strong>Artista: </strong><?php echo $ap['artist']; ?></p>
				<p><strong>Técnica: </strong><?php echo $ap['technique']; ?></p>
				<p><strong>Tamanho (cm): </strong><?php echo $ap['size']; ?></p>
				<p><strong>Doador: </strong><?php echo $ap['donor']; ?></p>
				<p><strong>Valor: </strong><?php echo formatDecimalToCurrency($ap['value']); ?></p>
				<p><strong>Localização: </strong><?php echo $ap['location']; ?></p>
				<p><?php echo $ap['year']; ?></p>
			</div>
			<div class="picBox">
				<?php if (isset($ap['mainImageAttachmentFileName'])): ?>
					<img src="<?php echo URL\URLGenerator::generateFileURL("uploads/art/$ap[id]/$ap[mainImageAttachmentFileName]"); ?>" />
				<?php else: ?>
					<img src="<?php echo URL\URLGenerator::generateFileURL("pics/nopic.png"); ?>" />
				<?php endif; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</body>
</html>