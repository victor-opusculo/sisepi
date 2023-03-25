<?php
require_once("checkLogin.php");
require_once("../includes/common.php");
require_once("../model/Database/libraryusers.database.php");

$user = null;
try
{
	$user = getSingleUser(($_GET["userId"] ?? ""));
}
catch (Exception $e)
{
	die($e->getMessage());
}

if ($user === null) die("Não foi possível ler os dados do usuário");
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style>
		table 
		{ 
			font-family: sans-serif;
			font-size: 14pt;
			width: 100%; 
			border: 1px solid black;
			border-collapse: collapse;
			margin-bottom: 2mm;
		}
		
		table, td { border: 1px solid black; padding: 2mm; }
		
		#liblogo
		{
			height: 2.8cm;
			width: auto;
		}
		
		#authTitle 
		{ 
			font-size: 24pt; 
		}
	</style>
</head>
<body>
	<table>
		<tbody>
			<tr>
				<td style="height: 3cm; width: 3cm;">
					<img id="liblogo" src="../pics/biblioteca.png"/> 
				</td>
				<td style="text-align: center; background-color: #eee;">
					<span id="authTitle">AUTORIZAÇÃO PARA EMPRÉSTIMO</span>
				</td>
			</tr>
		</tbody>
	</table>
	
	<table>
		<tbody>
			<tr>
				<td colspan="2">
					<span>Nome do Usuário: <?php echo hsc($user["name"]) . " (ID: " . $user["id"] . ")"; ?></span>
				</td>
			</tr>
			<tr>
				<td style="width: 50%">
					<span>(  ) Estagiário / (  ) Comissionado</span>
				</td>
				<td>
					<span>RG: </span>
				</td>
			</tr>
		</tbody>
	</table>
	
	<table>
		<tbody>
			<tr>
				<td colspan="2" style="text-align: center;">
					<span><strong>Autorização ao Usuário Temporário</strong></span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<span>Em conformidade com o Regimento Interno da Biblioteca Legislativa FHC, a chefia do órgão onde está lotado o usuário temporário supracitado, será em última instância responsável pelo material retirado.</span>
				</td>
			</tr>
			<tr>
				<td style="width: 30%; text-align: center;">
					<span><strong>Data</strong></span><br/><br/>
					<span>_____/_____/______</span>
				</td>
				<td style="text-align: center;">
					<span><strong>Assinatura do chefe do setor</strong></span><br/><br/>
					<span>______________________________________</span>
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>