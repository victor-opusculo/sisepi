<?php

namespace SisEpi\Model\Professors\Uploads;

require_once __DIR__ . '/../../../vendor/autoload.php';

use SisEpi\Model\FileUploadUtils;
use SisEpi\Model\Professors\Uploads\IrFileUploadException;

final class ProfessorInformeRendimentosUpload
{
    private function __construct() { }

    public const UPLOAD_DIR = 'uploads/professors/{profId}/informe_rendimentos/';
    public const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];
    public const MAX_SIZE = 5242880 /* 5MB */;

    /**
     * Processa o upload de arquivo de informe de rendimentos.
     * @param int $professorId ID do docente
     * @param int $year Ano de exercício do informe
     * @param array $filePostData Array $_FILES
     * @param string $fileInputElementName Nome do elemento do tipo file do formulário de upload
     * @return bool
     * @throws IrFileUploadException
     * */
    public static function uploadIrFile(int $professorId, int $year, array $filePostData, string $fileInputElementName) : bool
    {
            $fullDir = str_replace("{profId}", (string)$professorId, __DIR__ . "/../../../" . self::UPLOAD_DIR);
            $fileName = basename($filePostData[$fileInputElementName]["name"]);
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uploadFile = $fullDir . $year . '.' . $fileExtension;
        
            FileUploadUtils::checkForUploadError($filePostData[$fileInputElementName], self::MAX_SIZE, [self::class, 'throwException'], [ $fileName, $professorId ], self::ALLOWED_TYPES);

            if (!is_dir($fullDir))
                mkdir($fullDir, 0777, true);
                    
            if (!file_exists($uploadFile))
            {
                if (move_uploaded_file($filePostData[$fileInputElementName]["tmp_name"], $uploadFile))
                    return true;
                else
                    self::throwException("Erro ao mover o arquivo após upload.", $fileName, $professorId);
            }
            else
                self::throwException("Arquivo enviado já existente no servidor.", $fileName, $professorId);
            
            return false;		
    }

    public static function deleteIrFile(int $professorId, int $year, string $fileExtension) : bool
    {
        $locationFilePath = str_replace("{profId}", (string)$professorId, __DIR__ . "/../../../" . self::UPLOAD_DIR . $year . '.' . $fileExtension);
        
        if (file_exists($locationFilePath))
        {
            if(unlink($locationFilePath))
                return true;
            else
                self::throwException("Erro ao excluir o arquivo de informe de rendimentos.", basename($locationFilePath), $professorId);
        }
        return false;
    }

    public static function cleanIrFolder(int $professorId)
    {
        $fullDir = str_replace("{profId}", (string)$professorId, __DIR__ . "/../../../" . self::UPLOAD_DIR);
        
        if (is_dir($fullDir))
        {
            $files = glob($fullDir . "*"); // get all file names
            
            foreach($files as $file)
            {
                if(is_file($file)) 
                    unlink($file); // delete file
            }
        }
    }

    public static function checkForEmptyIrDir(int $professorId)
    {
        $fullDir = str_replace("{profId}", (string)$professorId, __DIR__ . "/../../../" . self::UPLOAD_DIR);
        
        if (is_dir($fullDir))
            if (dir_is_empty($fullDir))
                rmdir($fullDir);
    }

    public static function throwException(string $message, string $fileName, int $professorId)
    {
        throw new IrFileUploadException($message, $fileName, $professorId); 
    }
}
