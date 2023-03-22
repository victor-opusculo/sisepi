<?php

require_once "checkLogin.php";
require_once "../model/professors/ProfResumePDF.php";
require_once "../model/database/professors.database.php";
require_once "../model/database/generalsettings.database.php";
require_once "../includes/common.php";
require_once "../model/professors/Professor.php";
require_once "../vendor/autoload.php";

use \SisEpi\Model\Professors\Professor;

$profId = isset($_GET['professorId']) && isId($_GET['professorId']) ? $_GET['professorId'] : null;

$conn = createConnectionAsEditor();
$exception = "";
try
{
    $profGetter = new Professor();
    $profGetter->id = $profId;
    $profGetter->setCryptKey(getCryptoKey());
    $professor = $profGetter->getSingle($conn);

    $pdf = new \SisEpi\Model\Professors\ProfResumePDF($professor);
    $pdf->DrawPage();

    $pdfData = $pdf->Output('S');

    $profDocsAttachments = getProfessorPersonalDocs($profId, $conn);

    $fileZip = tempnam(sys_get_temp_dir(), 'zip');
    $zip = new ZipArchive();
    $fiveMBs = 5 * 1024 * 1024;
    $zip->open($fileZip, ZipArchive::OVERWRITE);
    $zip->addFromString("Currículo.pdf", $pdfData);

    if (!empty($profDocsAttachments))
        foreach ($profDocsAttachments as $i => $att)
        {
            $number = $i + 1;
            $filePath = SISEPI_BASEDIR . "/uploads/professors/$profId/docs/$att[fileName]";
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $zip->addFile($filePath, "{$number}_$att[typeName].{$extension}");
        }

    $zip->close();

    header('Content-Type: application/zip');
    header('Content-Length: ' . filesize($fileZip));
    header('Content-Disposition: filename="'.$professor->name.' - Documentos.zip"');

    readfile($fileZip);
    unlink($fileZip);
}
catch (Exception $e)
{
    $exception = $e->getMessage();
}
finally { $conn->close(); }

if ($exception) die($exception);

