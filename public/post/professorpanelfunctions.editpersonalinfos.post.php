<?php
require_once("../model/database/professorpanelfunctions.database.php");
require_once("../model/DatabaseEntity.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");
require_once("../includes/professorLoginCheck.php");

if (isset($_POST["btnsubmitProfessorEditPersonalInfos"]))
{
	$messages = [];
	
	$_POST['professors:profId'] = $_SESSION['professorid'];
	$dbEntity = new DatabaseEntity('Professor', $_POST);

	try
	{
		if (updateSingleProfessor($dbEntity))
		{
			$messages[] = "Cadastro atualizado!";
			writeLog("Dados de docente atualizados pelo próprio docente. id: " . $_POST['professors:profId'] . ". Nome: " . $_POST['professors:txtName']);
		}
		else
			throw new Exception("Nenhum dado alterado.");
	}
	catch (Exception $e)
	{
		$messages[] = $e->getMessage();
		writeErrorLog("Docente ao atualizar os próprios dados : {$e->getMessage()}. id: " . $_POST['professors:profId'] . ". Nome: " . $_POST['professors:txtName']);
	}
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, "messages=$messagesString"), true, 303);
}