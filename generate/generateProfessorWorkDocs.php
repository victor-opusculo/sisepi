<?php

require_once("checkLogin.php");
require_once("../includes/Professor/DocsPDF.php");
require_once("../includes/common.php");
require_once "../model/events/Event.php";
require("../includes/logEngine.php");
require("../model/database/professors2.database.php");
require("../model/database/professors.database.php");
require("../model/DatabaseEntity.php");

define('AUTH_ADDRESS',  getHttpProtocolName() . "://" . $_SERVER["HTTP_HOST"] . \URL\URLGenerator::generatePublicSystemURL("professors", "authsignature"));

$workSheetId = isset($_GET['workSheetId']) && isID($_GET['workSheetId']) ? $_GET['workSheetId'] : die('ID de ficha de trabalho não especificado.');

$workSheet = null;
$event = null;
$docTemplate = null;
$professor = null;

$conn = createConnectionAsEditor();
try
{
    $workSheet = new DatabaseEntity('ProfessorWorkSheet', getSingleWorkSheet($workSheetId, $conn));

    $eventGetter = new \SisEpi\Model\Events\Event();
    $eventGetter->id = $workSheet->eventId;
    $event = isset($workSheet->eventId) ? $eventGetter->getSingle($conn) : null;
    
    $docTemplate = isset($workSheet->professorDocTemplateId) ? new DatabaseEntity(null, getSingleDocTemplate($workSheet->professorDocTemplateId, $conn)) : null;
    $professor = new DatabaseEntity('Professor', getSingleProfessor($workSheet->professorId, $conn));
    $workSheet->_signatures = array_map( fn($dr) => new DatabaseEntity(null, $dr), getWorkDocSignatures($workSheet->id, $professor->id, $conn) ?? []);

    if (is_null($docTemplate))
        throw new Exception('Modelo de documentação não definido para esta ficha de trabalho.');
}
catch (Exception $e)
{
    writeErrorLog("Ao gerar PDF de documentação de trabalho de docente: {$e->getMessage()}. Ficha de trabalho id: " . $workSheetId);
    die ($e->getMessage());
}
finally
{
    $conn->close();
}

$profDocInfos = new Professor\ProfessorDocInfos($professor, $event, $workSheet);
$pdf = new Professor\DocsPDF();
$pdf->SetData($docTemplate->templateJson, $profDocInfos);

$pdf->GenerateDocument();

header('Content-Type: application/pdf');
header('Content-Disposition: filename="'.$professor->name.'.pdf"');

echo $pdf->output();

writeLog('PDF de documentação de trabalho de docente gerado. Ficha de trabalho id: ' . $workSheet->id);