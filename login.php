<?php
$loginMessage = "";

if (isset($_POST['submit']))
{
	require_once("model/database/user.settings.database.php");
	require_once("includes/URL/URLGenerator.php");
	require_once('includes/logEngine.php');

	session_name("sisepi_system_user");
	session_start();

	$userName = $_POST['username'];
	$password = $_POST['password'];

	$conn = createConnectionAsEditor();

	$userGot = getUser($userName, $password, $conn);

	if ($userGot)
	{
		$_SESSION['userid'] = $userGot["id"];
		$_SESSION['username'] = $userGot["name"];
		$_SESSION['passwordhash'] = $userGot["passwordHash"];
		$_SESSION['permissions'] = [];
		
		$userPermissions = getUserPermissions($userGot["id"], $conn);
		foreach ($userPermissions as $p)
		{
			if (!isset($_SESSION['permissions'][$p['permMod']]))
				$_SESSION['permissions'][$p['permMod']] = [];
			
			$_SESSION['permissions'][$p['permMod']][] = (int)$p['permId'];
		}
		
		writeLog("Log-in de usuário.");
		
		header('location:' . URL\URLGenerator::generateSystemURL("homepage"));
	}
	else
	{
		unset($_SESSION['username']);
		unset($_SESSION['passwordhash']);

		session_unset();
		session_destroy();
		
		writeErrorLog("Tentativa fracassada de log-in de usuário. Nome fornecido: $userName");
		
		$loginMessage = "Usuário e/ou senha incorretos!";
	}
	
	$conn->close();
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<!-- Desenvolvido por Victor Opusculo -->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<title>SisEPI - Sistema de Informações da Escola do Parlamento de Itapevi</title>
	<link rel="stylesheet" href="sisepi.css"/>
	<link rel="shortcut icon" type="image/x-icon" href="pics/favicon.ico">
</head>

<body>
	<main>
		<header>
			<div>
				<img src="pics/sisepilogo.png"/>
			</div>
		</header>  
		<div id="mainPageWrapper">
			<form method="post" action="login.php" id="formLogin">
				<fieldset id="fie">
					<legend>Log-in</legend><br/>
					<label>Nome:</label>
					<input type="text" name="username" id="login"/><br/>
					<label>Senha:</label>
					<input type="password" name="password" id="senha"/><br/><br/>
					<input type="submit" name="submit" value="Entrar"/>
					<label style="color: red;"><?php echo $loginMessage ?></label>
				</fieldset>
			</form>
			
		</div>
	</main>

	<div id="Logos" class="centControl">
			<img src="pics/EPI.jpg" alt="Escola do Parlamento de Itapevi" height="130" style="margin-right: 50px;"/>
			<img src="pics/CMI.jpg" alt="Câmara Municipal de Itapevi" height="80"/>
	</div>

	<footer>
			
	</footer>

</body>
</html>