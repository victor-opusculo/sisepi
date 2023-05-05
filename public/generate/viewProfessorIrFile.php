<?php

use SisEpi\Model\Database\Connection;
use SisEpi\Model\Professors\Uploads\ProfessorInformeRendimentosUpload;

require_once __DIR__ . '/../includes/professorLoginCheck.php';
require_once __DIR__ . '/../../vendor/autoload.php';

$exception = "";

if (!empty($_GET['id']))
{
    try
    {
        $conn = Connection::create();

        $getter = new \SisEpi\Model\Professors\ProfessorInformeRendimentosAttachment();
        $getter->id = $_GET['id'];
        $getter->professorId = $_SESSION['professorid'];
        $getter->setCryptKey(Connection::getCryptoKey());
        $IrFileAttachment = $getter->getSingleOfProfessor($conn);

        $fileNameFull = __DIR__ . '/../../' . str_replace("{profId}", $IrFileAttachment->professorId, ProfessorInformeRendimentosUpload::UPLOAD_DIR) . "{$IrFileAttachment->year}.{$IrFileAttachment->fileExtension}";

        if (!is_file($fileNameFull)) 
            throw new Exception("Arquivo não localizado.");
    
        // OUTPUT HTTP HEADERS
        header('Content-Disposition: filename="' . $IrFileAttachment->getOtherProperties()->professorName . '-' . "{$IrFileAttachment->year}.{$IrFileAttachment->fileExtension}" . '"');
        header("Content-Type: " . mime_content_type($fileNameFull));
        header("Content-Length: " . filesize($fileNameFull));
        
        // READ FILE
        readfile($fileNameFull);
    }
    catch (Exception $e)
    {
        $exception = $e->getMessage();
    }
    finally { $conn->close(); }

    if ($exception)
        die($exception);
}
else
    die ('ID não especificado.');
