<?php
session_start();
  require_once('includes/common.php');
  $mainframe = null;
  
    //Sessão de usuário:
	
	if((!isset ($_SESSION['username']) == true) and (!isset ($_SESSION['passwordhash']) == true))
	{
		unset($_SESSION['userid']);
		unset($_SESSION['username']);
		unset($_SESSION['passwordhash']);
		unset($_SESSION['permissions']);
		header('location:login.php');
	}
	else
	{
		$page = empty($_REQUEST['page']) ? 'home' : preg_replace('/[^a-zA-Z0-9\-_]/', '', $_REQUEST['page']);
		$pagepath = "controller/popup/$page.class.php";
		
		if (file_exists($pagepath)) 
		{
			require_once($pagepath);
			$pageclass = $page . 'Class';
		}
		else 
		{
			$pageclass = 'PopupBasePage';
		}
		
		$mainframe = new $pageclass($page);
	}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<!-- Desenvolvido por Victor Opusculo -->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title><?php if($mainframe) $mainframe->title();?></title>
	<link rel="stylesheet" href="<?php echo URL\URLGenerator::generateFileURL("sisepi.css"); ?>"/>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo URL\URLGenerator::generateFileURL("pics/favicon.ico"); ?>">
</head>

<body>
	<main>
		<!-- CONTENT AREA STARTS HERE -->
		<?php $mainframe->render();?>
		<!-- CONTENT AREA ENDS HERE -->
	</main>
</body>
</html>