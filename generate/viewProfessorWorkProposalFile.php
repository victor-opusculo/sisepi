<?php
require_once("checkLogin.php");
require_once("../model/Database/professors2.database.php");

function sanitizeFileName($fileName)
{
    return mb_ereg_replace('[\\\\/]', '', $fileName);
}

if (!empty($_GET['workProposalId']))
{
    $wpDr = getSingleWorkProposal($_GET['workProposalId']);

    if (!is_null($wpDr))
    {
        $fileName = $wpDr['id'] . '.' . $wpDr['fileExtension'];
        $fileNameFull = __DIR__ . '/../uploads/professors/workproposals/' . sanitizeFileName($fileName);

        if (!is_file($fileNameFull)) die("Arquivo não localizado.");
    
        // OUTPUT HTTP HEADERS
        
        header('Content-Disposition: filename="' . sanitizeFileName($fileName) . '"');
        header("Content-Type: " . mime_content_type($fileNameFull));
        header("Content-Length: " . filesize($fileNameFull));
        
        // READ FILE
        readfile($fileNameFull);
    }
    else
        die ("Proposta não localizada para este docente.");
    
}
else
    die ('ID de proposta não especificado.');