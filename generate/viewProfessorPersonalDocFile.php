<?php
require_once("checkLogin.php");

function sanitizeFileName($fileName)
{
    return mb_ereg_replace('[\\\\/]', '', $fileName);
}

if (!empty($_GET['file']) || !empty($_GET['professorId']))
{
    $fileNameFull = __DIR__ . '/../uploads/professors/' . $_GET['professorId'] . '/docs/' . sanitizeFileName($_GET['file']);

    if (!is_file($fileNameFull)) die("Arquivo não localizado.");
 
    // OUTPUT HTTP HEADERS
    header('Content-Disposition: filename="' . sanitizeFileName($_GET['file']) . '"');
    header("Content-Type: " . mime_content_type($fileNameFull));
    header("Content-Length: " . filesize($fileNameFull));
    
    // READ FILE
    readfile($fileNameFull);
}
else
    die ('Arquivo ou docente não especificado.');
