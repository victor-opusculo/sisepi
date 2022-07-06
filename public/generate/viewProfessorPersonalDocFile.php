<?php
require_once("../includes/professorLoginCheck.php");

function sanitizeFileName($fileName)
{
    return mb_ereg_replace('[\\\\/]', '', $fileName);
}

if (!empty($_GET['file']))
{
    $fileNameFull = __DIR__ . '/../../uploads/professors/' . $_SESSION['professorid'] . '/docs/' . sanitizeFileName($_GET['file']);

    if (!is_file($fileNameFull)) die("Arquivo não localizado.");
 
    // OUTPUT HTTP HEADERS
    header('Content-Disposition: filename="' . sanitizeFileName($_GET['file']) . '"');
    header("Content-Type: " . mime_content_type($fileNameFull));
    header("Content-Length: " . filesize($fileNameFull));
    
    // READ FILE
    readfile($fileNameFull);
}
else
    die ('Nenhum arquivo especificado.');