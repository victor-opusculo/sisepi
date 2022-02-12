<?php
require_once("checkLogin.php");
require_once("../includes/database/library.settings.database.php");

if(isset($_POST["btnsubmitChangeCategories"]) && checkUserPermission("LIBR", 1))
{
	$messages = [];
	if (applyCategoriesChangesReport($_POST["hiddenLibCategoriesJsonChangesReport"]))
		$messages[] = "Categorias atualizadas com sucesso!";
	else
		$messages[] = "Nenhum dado alterado.";
	
	$queryStrings = $_SERVER['QUERY_STRING'];
	$queryMessages = implode("//", $messages);
	header("location:../index.php?$queryStrings&messages=$queryMessages", true, 303);
}
else
	die(noPermissionMessage);