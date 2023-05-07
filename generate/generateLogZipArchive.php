<?php
require_once "checkLogin.php";
require_once "../includes/common.php";

$exception = "";
try
{
    if (!checkUserPermission("LOG", 1))
        throw new Exception("Usuário não tem permissão para visualizar logs.");

    $files = glob(SISEPI_BASEDIR . '/log/*.log');

    if (!empty($files))
    {
        $fileZip = tempnam(sys_get_temp_dir(), 'zip');
        $zip = new ZipArchive();
        $zip->open($fileZip, ZipArchive::OVERWRITE);

        foreach ($files as $file)
            $zip->addFile($file, basename($file));

        $zip->close();

        header('Content-Type: application/zip');
        header('Content-Length: ' . filesize($fileZip));
        header('Content-Disposition: filename="SisEPI - Logs.zip"');

        readfile($fileZip);
        unlink($fileZip);
    }
    else
        throw new Exception("Não há arquivos de log disponíveis.");
}
catch (Exception $e)
{
    $exception = $e->getMessage();
}
finally { $conn->close(); }

if ($exception) die($exception);

