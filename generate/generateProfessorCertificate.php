<?php
require_once "checkLogin.php";
require_once "../includes/common.php";
define('AUTH_ADDRESS',  getHttpProtocolName() . "://" . $_SERVER["HTTP_HOST"] . \URL\URLGenerator::generatePublicSystemURL("professors", "authcertificate"));

require_once "../includes/logEngine.php";
require_once "../includes/Professor/ProfessorCertificatePDF.php";
require_once "../model/Database/professors.database.php";
require_once "../model/Database/professors2.database.php";
require_once "../model/DatabaseEntity.php";

$workSheetId = isset($_GET['workSheetId']) && isId($_GET['workSheetId']) ? $_GET['workSheetId'] : die('ID de ficha de trabalho não especificado.');

$certId = null;
$issueDateTime = null;

$conn = createConnectionAsEditor();
try
{
	$workSheet = new DatabaseEntity('ProfessorWorkSheet', getSingleWorkSheet($workSheetId, $conn));
	$professor = new DatabaseEntity('Professor', getSingleProfessor($workSheet->professorId, $conn));

	if (!$workSheet->professorCertificateText)
		die("Esta ficha de trabalho não fornece certificados automaticamente.");	

	if ($alreadyIssuedCertificateDataRow = isProfessorCertificateAlreadyIssued($workSheet->id, $conn))
	{
		$certId = $alreadyIssuedCertificateDataRow["id"];
		$issueDateTime = $alreadyIssuedCertificateDataRow["dateTime"];
	}
	else
	{
		$issueDateTime = (string)date("Y-m-d H:i:s");
		$certId = saveProfessorCertificateInfos($workSheet->id, $issueDateTime, $conn);
	}
}
catch (Exception $e)
{
	writeErrorLog('Ao gerar certificado de docente: {$e->getMessage()}.');
	$exception = $e->getMessage();
}
finally { $conn->close(); }

if (!empty($exception)) die($exception);

$pdf = new Professor\ProfessorCertificatePDF($professor, $workSheet, [ "code" => $certId, "issueDateTime" => $issueDateTime ]);
$pdf->DrawFrontPage();
$pdf->DrawBackPage();

header('Content-Type: application/pdf');
header('Content-Disposition: filename="'.$professor->name.'.pdf"');

echo $pdf->output('S');

writeLog("Certificado de docente gerado. id: $certId. Ficha de trabalho id: {$workSheet->id}.");