<?php
session_name("sisepi_system_user");
session_start();

define("noPermissionMessage", "Você não tem permissão para executar esta função ou você não veio de uma página válida do sistema.");

if (!function_exists('checkUserPermission'))
{
	function checkUserPermission($module, $id) 
	{
		if (!isset($_SESSION['permissions'][$module]))
			return false;
		
		return in_array($id, $_SESSION['permissions'][$module]);
	}
}

if((!isset ($_SESSION['username']) == true) and (!isset ($_SESSION['passwordhash']) == true))
{
	unset($_SESSION['userid']);
	unset($_SESSION['username']);
	unset($_SESSION['passwordhash']);
	unset($_SESSION['permissions']);
	header('location:../login.php');
	exit();
}