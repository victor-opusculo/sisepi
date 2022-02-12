﻿<?php
//Public
require_once('includes/common.php');

$controller = empty($_REQUEST['cont']) ? 'homepage' : preg_replace('/[^a-zA-Z0-9\-_]/', '', $_REQUEST['cont']);
$action = empty($_REQUEST['action']) ? 'home' : preg_replace('/[^a-zA-Z0-9\-_]/', '', $_REQUEST['action']);

$controllerPath = "controller/$controller.controller.php";

if (file_exists($controllerPath)) 
{
	require_once($controllerPath);
	$controllerClass = $controller;
}
else 
{
	$controllerClass = 'BaseController';
}

$mainframe = new $controllerClass($action);
 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<!-- Desenvolvido por Victor Opusculo -->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<title><?php if ($mainframe) $mainframe->title();?></title>
	<link rel="stylesheet" href="<?php echo URL\URLGenerator::generateFileURL("sisepi_public.css"); ?>"/>
	<style>
		nav { text-align: center; }
		nav ul { margin: 0; padding-left: 0; }
		nav li { display: inline-block; margin-right: 10px; border-bottom: solid 2px transparent; }
		nav li:hover { border-bottom: solid 2px red; }

		nav a 
		{
			text-decoration: none; 
			font-family: sans-serif;
			color: white;
		}
	
		nav li ul
		{
			position: absolute;
			background-color: #585858;
			display: none;
			width: max-content;
		}

		nav li ul li:hover 
		{
			background-color: #999; 
		}
		
		nav li:hover ul
		{
			position: absolute;
			display: block;
			animation: growDown 300ms ease-in-out forwards;
			transform-origin: top center;
		}
		
		nav li ul li
		{
			display: block;
			padding: 5px;
			margin:0;
		}

		nav li ul li a
		{
			display: block;
		}
		
		nav li.dropdown
		{
			position: relative;
		}

		@keyframes growDown 
		{
			0% { transform: scaleY(0); }
			80% { transform: scaleY(1.1); }
			100% { transform: scaleY(1); }
		}
	</style>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/favicon.ico"); ?>">
	<script>
		function setTableCellsHeadNameAttribute()
		{
			if (document.querySelector("table"))
			{	
				document.querySelectorAll("table").forEach( table =>
				{
					var headerCells = table.querySelectorAll("thead th");
					
					if (headerCells.length > 0)
						table.querySelectorAll("tbody tr").forEach( (tr, tri) =>
						{
							tr.querySelectorAll("td").forEach( (td, tdi) =>
							{
								let innerText = headerCells[tdi].firstChild?.wholeText;
								if (innerText) td.setAttribute("data-th", innerText);
							});
						});
				});
			}
		}
		
		window.addEventListener("load", setTableCellsHeadNameAttribute);
	</script>
</head>

<body>
	<main>
		<header>
			<div style="display: flex;">
				<div style="flex-basis: 210px;">
					<img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/sisepilogo.png"); ?>" height="150"/>
				</div>
				<div style="flex-grow: 1;
							display: flex;
							justify-content: center;
							align-items: center;
							font-weight: bold;
							text-shadow: 2px 2px 3px lightgray;">O Sistema de Informações da Escola do Parlamento de Itapevi "Doutor Osmar de Souza"
				</div>
			</div>
			<div id="mainMenu">
				<nav>
					<ul>
						<li><a href="<?php echo URL\URLGenerator::generateSystemURL("homepage"); ?>">Início</a></li>
						<li><a href="<?php echo URL\URLGenerator::generateSystemURL("librarycollection"); ?>">Biblioteca</a></li>
						<li class="dropdown">
							<a href="#">Eventos</a>
							<ul>
								<li><a href="<?php echo URL\URLGenerator::generateSystemURL("events"); ?>">Ver Eventos</a></li>
								<li><a href="<?php echo URL\URLGenerator::generateSystemURL("events", "authcertificate"); ?>">Autenticar Certificado</a></li>
							</ul>
						</li>
						<li><a href="<?php echo URL\URLGenerator::generateSystemURL("professors", "register"); ?>">Cadastro de Docentes</a></li>
						<li><a href="<?php echo URL\URLGenerator::generateSystemURL("artmuseum"); ?>">Museu de Arte</a></li>
						<li><a href="<?php echo URL\URLGenerator::generateSystemURL("mailing"); ?>">Mailing</a></li>
					</ul>
					
				</nav>
			</div>
		</header>  
		<div id="mainPageWrapper">
			<?php if ($mainframe->hasSubtitle()): ?>
			<h2><?php $mainframe->subtitle(); ?></h2>
			<?php endif; ?>
			<?php if (count($mainframe->pageMessages) > 0) { ?>
			<div class="pageMessagesFrame">
				<?php 
				foreach ($mainframe->pageMessages as $m)
				{
					echo "<p>" . $m . "</p>";
				}
				?>
			</div>
			<?php } ?>
			<!-- CONTENT AREA STARTS HERE -->
			<?php $mainframe->render();?>
			<!-- CONTENT AREA ENDS HERE -->
		</div>
	</main>

	<div id="Logos" class="centControl">
			<img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/EPI.jpg"); ?>" alt="Escola do Parlamento de Itapevi" height="130" style="margin-right: 50px;"/>
			<img src="<?php echo URL\URLGenerator::generateBaseDirFileURL("pics/CMI.jpg"); ?>" alt="Câmara Municipal de Itapevi" height="80"/>
	</div>

	<footer>
			
	</footer>

</body>
</html>