<?php
session_name("sisepi_system_user");
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once("../model/database/eventchecklists.database.php");

$success = false;
$message = "";

function checkUserPermission($module, $id) 
{
	if (!isset($_SESSION['permissions'][$module]))
		return false;
	
	return in_array($id, $_SESSION['permissions'][$module]);
}

try
{
    if((!isset ($_SESSION['username']) == true) and (!isset ($_SESSION['passwordhash']) == true))
    {
        unset($_SESSION['userid']);
        unset($_SESSION['username']);
        unset($_SESSION['passwordhash']);
        unset($_SESSION['permissions']);
        throw new Exception('Erro: Você não está logado.');
    }
    else if (!checkUserPermission('CHKLS', 4))
    {
        throw new Exception('Erro: Você não tem permissão para preencher checklists.');
    }
    else
    {
        $json = file_get_contents('php://input');

        if ($checklistChangeReport = json_decode($json))
        {
            $returnInfos = setChecklistItemValue($checklistChangeReport->checklistId, $checklistChangeReport->itemPath, $checklistChangeReport->value);
            if ($returnInfos['affectedRows'] > 0)
            {
                $success = true;
                $message = 'Alteração salva com sucesso!';
            } 
                else throw new Exception('Erro: Alteração não salva.');
           
            if ($returnInfos['isChecklistCompleted']) $message .= " | Este checklist foi completado!";
        }
        else
            throw new Exception('Erro: Não foi possível decodificar o texto JSON');
    }
}
catch (Exception $e)
{
    $success = false;
    $message = $e->getMessage();
}

echo json_encode(
[ 
    "success" => $success,
    "message" => $message
]);