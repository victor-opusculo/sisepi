<?php

require_once("checkLogin.php");
require_once("../model/vereadormirim/DocumentPDF.php");
require_once "../includes/common.php";
require_once "../model/vereadormirim/Student.php";
require_once "../model/vereadormirim/VmParent.php";
require_once("../includes/logEngine.php");
require_once("../model/database/database.php");

define('AUTH_ADDRESS',  getHttpProtocolName() . "://" . $_SERVER["HTTP_HOST"] . \URL\URLGenerator::generatePublicSystemURL("vereadormirim", "authdocsignature"));

$documentId = isset($_GET['documentId']) && isID($_GET['documentId']) ? $_GET['documentId'] : die('ID de documento de vereador mirim não especificado.');

$documentObj = null;
$vmStudentObj = null;
$vmParentObj = null;

$conn = createConnectionAsEditor();
try
{
    $docGetter = new \SisEpi\Model\VereadorMirim\Document();
    $docGetter->id = $documentId;
    $docGetter->setCryptKey(getCryptoKey());
    $documentObj = $docGetter->getSingle($conn);
    $documentObj->fetchSignatures($conn);

    foreach ($documentObj->signatures as $sign)
    {
        $sign->setCryptKey(getCryptoKey());
        $sign->fetchSigner($conn);
    } 

    $studentGetter = new \SisEpi\Model\VereadorMirim\Student();
    $studentGetter->id = $documentObj->vmStudentId;
    $studentGetter->setCryptKey(getCryptoKey());
    $vmStudentObj = $studentGetter->getSingle($conn);

    $parentGetter = new \SisEpi\Model\VereadorMirim\VmParent();
    $parentGetter->id = $vmStudentObj->vmParentId;
    $parentGetter->setCryptKey(getCryptoKey());
    $vmParentObj = isset($vmStudentObj->vmParentId) ? $parentGetter->getSingle($conn) : null;
    
    $docTemplate = $documentObj->getOtherProperties()->templateJson ?? null;

    if (is_null($docTemplate))
        throw new Exception('Modelo de documento não definido para este documento de vereador mirim.');
}
catch (Exception $e)
{
    writeErrorLog("Ao gerar PDF de documento de vereador mirim: {$e->getMessage()}. Documento id: " . $documentId);
    die ($e->getMessage());
}
finally
{
    $conn->close();
}

$docInfos = new \SisEpi\Model\VereadorMirim\DocumentInfos($documentObj, $vmStudentObj, $vmParentObj);
$pdf = new \SisEpi\Model\VereadorMirim\DocumentPDF();
$pdf->SetData($docTemplate, $docInfos);

$pdf->GenerateDocument();

header('Content-Type: application/pdf');
header('Content-Disposition: filename="'.$vmStudentObj->name.'.pdf"');

echo $pdf->output();

writeLog('PDF de documento de verador mirim gerado. Documento id: ' . $documentId);