<?php

require_once '../../includes/professorLoginCheck.php';
require_once("../../includes/Professor/DocsPDF.php");
require("../includes/logEngine.php");
require("../model/database/professorpanelfunctions.database.php");
require("../model/DatabaseEntity.php");

//Uses base dir URLGenerator
define('AUTH_ADDRESS',  getHttpProtocolName() . "://" . $_SERVER["HTTP_HOST"] . URL\URLGenerator::generatePublicSystemURL("professors", "authsignature"));

$workSheetId = isset($_GET['workSheetId']) && isID($_GET['workSheetId']) ? $_GET['workSheetId'] : die('ID de ficha de trabalho não especificado.');

$workSheet = null;
$event = null;
$docTemplate = null;
$professor = null;

$conn = createConnectionAsEditor();
try
{
    $workSheet = new DatabaseEntity('ProfessorWorkSheet', getSingleWorkSheet($_SESSION['professorid'], $workSheetId, $conn));
    $event = isset($workSheet->eventId) ? new DatabaseEntity('event', getSingleEvent($workSheet->eventId, $conn)) : null;
    $docTemplate = isset($workSheet->professorDocTemplateId) ? new DatabaseEntity(null, getSingleDocTemplate($workSheet->professorDocTemplateId, $conn)) : null;
    $professor = new DatabaseEntity('Professor', getSingleProfessor($_SESSION['professorid'], $conn));
    $workSheet->_signatures = array_map( fn($dr) => new DatabaseEntity(null, $dr), getWorkDocSignatures($workSheet->id, $professor->id, $conn) ?? []);

    if (is_null($docTemplate))
        throw new Exception('Modelo de documentação não definido para esta ficha de trabalho.');

    if (date_create($workSheet->signatureDate) > new DateTime('now'))
        throw new Exception('Erro: Você ainda não pode visualizar a documentação.');
}
catch (Exception $e)
{
    writeErrorLog("Docente ao gerar PDF de documentação de trabalho de docente: {$e->getMessage()}. Ficha de trabalho id: " . $workSheetId);
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

writeLog('PDF de documentação de trabalho de docente gerado pelo próprio docente. Ficha de trabalho id: ' . $workSheet->id);