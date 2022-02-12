<style>
		a.moduleLink
		{
			display: block;
			margin: 10px;
			margin-left: auto;
			margin-right: auto;
			width: 200px;
			font-size: 110%;
			padding: 3px 50px 3px 50px;
			border: solid 1px #BBB;
			border-radius: 5px;
			font-weight: bold;
			color: black;
			background-color: #EEE;
			text-decoration: none;
			box-shadow: inset 0px -7px 10px 0 rgba(0, 0, 0, 0.12);
			transition-duration: 0.1s;
		}
		
		a.moduleLink:hover { background-color: #DDD; }
		
		a.moduleLink:active
		{
			background-color: #DDD;
			box-shadow: inset 0px 7px 10px 0 rgba(0, 0, 0, 0.12);
		}
</style>

<div class="centControl">
	<a class="moduleLink" href="<?php echo URL\URLGenerator::generateSystemURL("librarycollection"); ?>">Acervo</a>
	<a class="moduleLink" href="<?php echo URL\URLGenerator::generateSystemURL("libraryusers"); ?>">Usuários</a>
	<a class="moduleLink" href="<?php echo URL\URLGenerator::generateSystemURL("libraryborrowedpubs"); ?>">Empréstimos</a>
	<a class="moduleLink" href="<?php echo URL\URLGenerator::generateSystemURL("libraryreservations"); ?>">Reservas</a>
</div>