<?php
require_once("../model/database/professorRegistration.database.php");
require_once("../includes/URL/URLGenerator.php");

if (isset($_POST["btnsubmitProfessorRegistration"]))
{
	$messages = [];
	
	try
	{
		if (insertProfessorData($_POST))
			$messages[] = "Obrigado por se cadastrar!";
		else
			$messages[] = "Cadastro não efetuado.";
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, "messages=$messagesString"), true, 303);
}