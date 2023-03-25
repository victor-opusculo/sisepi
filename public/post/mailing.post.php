<?php
require_once("../model/Database/mailing.database.php");
require_once("../includes/URL/URLGenerator.php");
require_once("../includes/logEngine.php");

$messages = [];

if ($_POST["btnsubmitRegister"])
{
	$conn = createConnectionAsEditor();
	
	if (checkIfMailingContains($_POST["txtEmail"], $conn))
		array_push($messages, "Este e-mail já está cadastrado em nosso mailing.");
	else
	{
		if (createMailingSubscription($_POST["txtEmail"], $_POST["txtName"], $conn))
		{
			array_push($messages, "E-mail cadastrado com sucesso!");
			writeLog("E-mail cadastrado no mailing via página de mailing: " . $_POST['txtEmail'] . ". Nome: " . $_POST['txtName']);
		}
		else
		{
			array_push($messages, "Erro ao cadastrar o e-mail.");
			writeErrorLog("Ao cadastrar e-mail no mailing via página de mailing:" . $_POST['txtEmail'] . ". Nome: " . $_POST['txtName']);
		}
	}
	
	$conn->close();
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'] ?? '', null, "messages=$messagesString"), true, 303);
}

if ($_POST["btnsubmitDelete"])
{
	$conn = createConnectionAsEditor();
	
	if (checkIfMailingContains($_POST["txtEmail"], $conn))
	{
		if(deleteMailingSubscription($_POST["txtEmail"], $conn))
		{
			array_push($messages, "Cadastro removido com sucesso!");
			writeLog("E-mail removido do mailing via página de mailing: " . $_POST['txtEmail']);
		}
		else
		{
			array_push($messages, "Erro ao remover o cadastro.");
			writeErrorLog("Ao remover e-mail do mailing via página de mailing: " . $_POST['txtEmail']);
		}
	}
	else
		array_push($messages, "Este e-mail não está cadastrado em nosso mailing.");

	$conn->close();
	
	$messagesString = implode("//", $messages);
	header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'] ?? '', null, "messages=$messagesString"), true, 303);
}