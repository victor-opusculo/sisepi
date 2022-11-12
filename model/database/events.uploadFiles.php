<?php

class EventFileUploadException extends Exception
{
	public $eventId;

	public function __construct($errMessage, $fileName, $eventId)
	{
		parent::__construct($errMessage . " | Arquivo: " . $fileName);
		$this->eventId = $eventId;
	}
}

define("eventUploadDir", "uploads/events/");

function uploadEventFile($eventId, $filePostData, $fileInputElementName)
{
		$fullDir = __DIR__ . "/../../" . eventUploadDir . $eventId . "/";
		$fileName = basename($filePostData[$fileInputElementName]["name"]);
		$uploadEventFile = $fullDir . $fileName;
		
		switch ($filePostData[$fileInputElementName]["error"])
		{
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new EventFileUploadException("Erro no upload: Tamanho máximo de arquivo excedido!", $fileName, $eventId); break;
			case UPLOAD_ERR_PARTIAL:
				throw new EventFileUploadException("Erro no upload: Arquivo com upload parcialmente feito!", $fileName, $eventId); break;
			case UPLOAD_ERR_NO_FILE:
				throw new EventFileUploadException("Erro no upload: Nenhum arquivo definido para o upload!", $fileName, $eventId); break;
			case UPLOAD_ERR_NO_TMP_DIR:
				throw new EventFileUploadException("Erro no upload: Diretório temporário de upload ausente!", $fileName, $eventId); break;
			case UPLOAD_ERR_CANT_WRITE:
				throw new EventFileUploadException("Erro no upload: Arquivo de upload não pôde ser gravado em disco!", $fileName, $eventId); break;
			case UPLOAD_ERR_OK: 
			default:
				break;
		}
		
		if (!is_dir($fullDir))
			mkdir($fullDir, 0777, true);
				
		if (!file_exists($uploadEventFile))
		{
			if (move_uploaded_file($filePostData[$fileInputElementName]["tmp_name"], $uploadEventFile))
				return true;
			else
				die("Erro ao mover o arquivo após upload.");
		}
		else
			die("Arquivo anexo já existente no servidor.");
		
		return false;
}

function deleteEventFile($eventId, $fileName)
{
	$locationFilePath = __DIR__ . "/../../" . eventUploadDir . $eventId . "/" . $fileName;
	
	if (file_exists($locationFilePath))
	{
		if(unlink($locationFilePath))
		{
			checkForEmptyEventDir($eventId);
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
	$fullDir = __DIR__ . "/../../" . eventUploadDir . $eventId . "/";
	
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

function checkForEmptyEventDir($eventId)
{
	$fullDir = __DIR__ . "/../../" . eventUploadDir . $eventId . "/";
	
	if (is_dir($fullDir))
		if (event_dir_is_empty($fullDir))
		{
			rmdir($fullDir);
		}
}

function event_dir_is_empty($dir) 
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