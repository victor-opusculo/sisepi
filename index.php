﻿<?php
  require_once('includes/common.php');
  $mainframe = null;
  
    //Sessão de usuário:
	session_start();
	if((!isset ($_SESSION['username']) == true) and (!isset ($_SESSION['passwordhash']) == true))
	{
		unset($_SESSION['userid']);
		unset($_SESSION['username']);
		unset($_SESSION['passwordhash']);
		unset($_SESSION['permissions']);
		header('location:' . URL\URLGenerator::generateFileURL('login.php'));
	}
	else
	{
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
	}
  
	$loggedUser = $_SESSION['username'] ?? null;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<!-- Desenvolvido por Victor Opusculo -->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<title><?php if($mainframe) $mainframe->title();?></title>
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
	<link rel="stylesheet" href="<?php echo URL\URLGenerator::generateFileURL("sisepi.css"); ?>"/>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo URL\URLGenerator::generateFileURL("pics/favicon.ico"); ?>">
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
			<div>
				<img src="<?php echo URL\URLGenerator::generateFileURL("pics/sisepilogo.png"); ?>" height="150"/>
			</div>
			<div id="mainMenu">
				<nav>
					<ul>
						<li><a href="<?php echo URL\URLGenerator::generateSystemURL("homepage"); ?>">Início</a></li>
						<li class="dropdown">
							<a href="<?php echo URL\URLGenerator::generateSystemURL("library"); ?>">Biblioteca</a>
							<ul>
								<li><a href="<?php echo URL\URLGenerator::generateSystemURL("librarycollection"); ?>">Acervo</a></li>
								<li><a href="<?php echo URL\URLGenerator::generateSystemURL("libraryusers"); ?>">Usuários</a></li>
								<li><a href="<?php echo URL\URLGenerator::generateSystemURL("libraryborrowedpubs"); ?>">Empréstimos</a></li>
								<li><a href="<?php echo URL\URLGenerator::generateSystemURL("libraryreservations"); ?>">Reservas</a></li>
							</ul>
						</li>
						<li><a href="<?php echo URL\URLGenerator::generateSystemURL("events"); ?>">Eventos</a></li>
						<li><a href="<?php echo URL\URLGenerator::generateSystemURL("professors"); ?>">Docentes</a></li>
						<li><a href="<?php echo URL\URLGenerator::generateSystemURL("mailing"); ?>">Mailing</a></li>
						<li><a href="<?php echo URL\URLGenerator::generateSystemURL("artmuseum"); ?>">Museu de Arte</a></li>
						<li><a href="<?php echo URL\URLGenerator::generateSystemURL("settings"); ?>">Configurações</a></li>
					</ul>
					
				</nav>
			</div>
		</header>  
		<div id="mainPageWrapper">
			<label style="font-size: medium;">Você está logado(a) como: <?php echo $loggedUser ?> (<a href="<?php echo URL\URLGenerator::generateFileURL('logout.php'); ?>">Sair</a>)</label>
			<?php if ($mainframe && $mainframe->hasSubtitle()): ?>
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
			<?php if($mainframe) $mainframe->render();?>
			<!-- CONTENT AREA ENDS HERE -->
		</div>
	</main>

	<div id="Logos" class="centControl">
			<img src="<?php echo URL\URLGenerator::generateFileURL("pics/EPI.jpg"); ?>" alt="Escola do Parlamento de Itapevi" height="130" style="margin-right: 50px;"/>
			<img src="<?php echo URL\URLGenerator::generateFileURL("pics/CMI.jpg"); ?>" alt="Câmara Municipal de Itapevi" height="80"/>
	</div>

	<footer>
			
	</footer>

</body>
</html>