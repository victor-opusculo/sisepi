<?php
require_once("checkLogin.php");
require_once("../includes/common.php");
require_once("../model/database/librarycollection.database.php");
require_once "../model/librarycollection/Publication.php";

$conn = createConnectionAsEditor();
$data = (new \SisEpi\Model\LibraryCollection\Publication)->getAllForTags($conn, $_GET['orderBy'] ?? '', $_GET['q'] ?? '');
$conn->close();

if (empty($data)) die("Não há dados de acordo com o critério atual de pesquisa.");

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Etiquetas para o acervo</title>
		<style>
			* { box-sizing: border-box; }
			body { font-size: 10pt; margin: 0.5cm;}
		
			.rightControl { text-align: right; }
		
			.stickerFrame
			{
				display: inline-block;
				width: 9.9cm;
				height: 3.5cm;
				border: 1px solid black;
				margin: 0;
				position: relative;
			}
			
			div.spineSection
			{
				position:absolute;
				padding: 0.2cm;
				margin: 0;
				left: 0.5cm;
				top: 0;
				bottom: 0;
				width: 3.25cm;
			}
			
			div.coverSection
			{
				font-weight: bold;
				position:absolute;
				padding: 0.2cm;
				left: 3.25cm;
				top: 0;
				bottom: 0;
				right: 0;
				overflow: hidden;
			}
			
			div.bottomInfos
			{
				position: absolute;
				padding: 0.2cm;
				bottom: 0;
				left: 0;
				right: 0;
				background-color: white;
			}
			
			div.bottomInfos img
			{
				width: 0.9cm;
				height: auto;
			}
		</style>
	<head>
	<body>
	<?php foreach ($data as $p):
		?><div class="stickerFrame">
			<div class="spineSection">
				BLPFHC<br/>
				ID: <?php echo $p["id"]; ?><br/>
				<strong><?php echo hsc($p["cdd"]); ?></strong>

				<div class="bottomInfos">
					<?php echo hsc($p["volume"]); ?><br/>
					<?php echo hsc($p["year"]); ?>/<?php echo hsc($p["month"]); ?>/<?php echo hsc($p["number"]); ?><br/>
					<?php echo hsc($p["copyNumber"]); ?>
				</div>
			</div>
			<div class="coverSection">
				Autor: <?php echo hsc($p["author"]); ?><br/>
				Título: <?php echo hsc($p["title"]); ?>
				
				<div class="bottomInfos rightControl">
					<?php echo ($p["cdd"]); ?> <?php echo hsc($p["number"]); ?>/<?php echo hsc($p["month"]); ?> <?php echo hsc($p["copyNumber"]); ?>
					<img src="../pics/CMI.jpg"/>
					<img src="../pics/EPI.jpg"/>
				</div>
			</div>
		</div><?php endforeach; ?>
	</body>
<html>