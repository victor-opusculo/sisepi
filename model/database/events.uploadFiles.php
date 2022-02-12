<?php

class FileUploadException extends Exception
{
	public $eventId;

	public function __construct($errMessage, $fileName, $eventId)
	{
		parent::__construct($errMessage . " | Arquivo: " . $fileName);
		$this->eventId = $eventId;
	}
}

define("uploadDir", "uploads/events/");

function uploadFile($eventId, $filePostData, $fileInputElementName)
{
		$fullDir = __DIR__ . "/../../" . uploadDir . $eventId . "/";
		$fileName = basename($filePostData[$fileInputElementName]["name"]);
		$uploadFile = $fullDir . $fileName;
		
		switch ($filePostData[$fileInputElementName]["error"])
		{
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new FileUploadException("Erro no upload: Tamanho máximo de arquivo excedido!", $fileName, $eventId); break;
			case UPLOAD_ERR_PARTIAL:
				throw new FileUploadException("Erro no upload: Arquivo com upload parcialmente feito!", $fileName, $eventId); break;
			case UPLOAD_ERR_NO_FILE:
				throw new FileUploadException("Erro no upload: Nenhum arquivo definido para o upload!", $fileName, $eventId); break;
			case UPLOAD_ERR_NO_TMP_DIR:
				throw new FileUploadException("Erro no upload: Diretório temporário de upload ausente!", $fileName, $eventId); break;
			case UPLOAD_ERR_CANT_WRITE:
				throw new FileUploadException("Erro no upload: Arquivo de upload não pôde ser gravado em disco!", $fileName, $eventId); break;
			case UPLOAD_ERR_OK: 
			default:
				break;
		}
		
		if (!is_dir($fullDir))
		{
			mkdir($fullDir, 0777, true);
		}
				
		if (!file_exists($uploadFile))
		{
			if (move_uploaded_file($filePostData[$fileInputElementName]["tmp_name"], $uploadFile))
			{
				return true;
			}
			else
			{
				die("Erro ao mover o arquivo após upload.");
			}
		}
		else
			die("Arquivo anexo já existente no servidor.");
		
		return false;
		
}

function deleteFile($eventId, $fileName)
{
	$locationFilePath = __DIR__ . "/../../" . uploadDir . $eventId . "/" . $fileName;
	
	if (file_exists($locationFilePath))
	{
		if(unlink($locationFilePath))
		{
			return true;
		}
		else
		{
			die("Erro ao excluir o arquivo anexo.");
			return false;
		}
	}
	return false;
}

function cleanEventFolder($eventId)
{
	$fullDir = __DIR__ . "/../../" . uploadDir . $eventId . "/";
	
	if (is_dir($fullDir))
	{
		$files = glob($fullDir . "*"); // get all file names
		
		foreach($files as $file)
		{
			if(is_file($file)) 
			{
				unlink($file); // delete file
			}
		}
	}
}

function checkForEmptyDir($eventId)
{
	$fullDir = __DIR__ . "/../../" . uploadDir . $eventId . "/";
	
	if (is_dir($fullDir))
		if (dir_is_empty($fullDir))
		{
			rmdir($fullDir);
		}
}

function dir_is_empty($dir) 
{
  $handle = opendir($dir);
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      closedir($handle);
      return false;
    }
  }
  closedir($handle);
  return true;
}