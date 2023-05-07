<?php
require_once "../includes/common.php";
require_once "checkLogin.php";

$messages = [];
try
{
    if (!checkUserPermission("LOG", 2))
        throw new Exception("Usuário não tem permissão para excluir arquivo de log.");

    $file = SISEPI_BASEDIR . "/log/" . $_GET['file'];

    if (!file_exists($file))
        throw new Exception("Arquivo especificado não existe.");

    if (unlink($file))
        $messages[] = "Arquivo de log excluído com sucesso!";
    else
        throw new Exception("Não foi possível excluir o arquivo.");
}
catch (Exception $e)
{
    $messages[] = $e->getMessage();
}

$messagesString = implode("//", $messages);
header("location:" . URL\URLGenerator::generateSystemURL($_GET['cont'], $_GET['action'], null, [ 'messages' => $messagesString ]), true, 303);