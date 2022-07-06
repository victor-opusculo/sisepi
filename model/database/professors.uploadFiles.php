<?php

class FileUploadException extends Exception
{
	public $professorId;
	
	public function __construct($errMessage, $fileName, $professorId = null)
	{
		parent::__construct($errMessage . " | Arquivo: " . $fileName);
		$this->professorId = $professorId;
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

define("uploadDir", "uploads/professors/");

function uploadPersonalDocFile($professorId, $filePostData, $fileInputElementName)
{
		$fullDir = __DIR__ . "/../../" . uploadDir . $professorId . "/docs/";
		$fileName = basename($filePostData[$fileInputElementName]["name"]);
		$uploadFile = $fullDir . $fileName;
	
		switch ($filePostData[$fileInputElementName]["error"])
		{
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new FileUploadException("Erro no upload: Tamanho máximo de arquivo excedido!", $fileName, $professorId); break;
			case UPLOAD_ERR_PARTIAL:
				throw new FileUploadException("Erro no upload: Arquivo com upload parcialmente feito!", $fileName, $professorId); break;
			case UPLOAD_ERR_NO_FILE:
				throw new FileUploadException("Erro no upload: Nenhum arquivo definido para o upload!", $fileName, $professorId); break;
			case UPLOAD_ERR_NO_TMP_DIR:
				throw new FileUploadException("Erro no upload: Diretório temporário de upload ausente!", $fileName, $professorId); break;
			case UPLOAD_ERR_CANT_WRITE:
				throw new FileUploadException("Erro no upload: Arquivo de upload não pôde ser gravado em disco!", $fileName, $professorId); break;
			case UPLOAD_ERR_OK: 
			default:
				break;
		}

		if ($filePostData[$fileInputElementName]['size'] > 1887436.8 /* 1,8MB */)
			throw new FileUploadException("Erro no upload: Arquivo acima do tamanho permitido!", $fileName, $professorId);
		
		$mimeType = $filePostData[$fileInputElementName]['type'];
		if ($mimeType !== 'image/jpeg' && $mimeType !== 'image/png' && $mimeType !== 'application/pdf')
			throw new FileUploadException("Erro no upload: Arquivo em formato não suportado!", $fileName, $professorId);

		if (!is_dir($fullDir))
			mkdir($fullDir, 0777, true);
				
		if (!file_exists($uploadFile))
		{
			if (move_uploaded_file($filePostData[$fileInputElementName]["tmp_name"], $uploadFile))
				return true;
			else
				die("Erro ao mover o arquivo após upload.");
		}
		else
			die("Arquivo enviado já existente no servidor.");
		
		return false;		
}

function deleteDocsFile($professorId, $fileName)
{
	$locationFilePath = __DIR__ . "/../../" . uploadDir . $professorId . "/docs/" . $fileName;
	
	if (file_exists($locationFilePath))
	{
		if(unlink($locationFilePath))
			return true;
		else
			die("Erro ao excluir o arquivo anexo.");
	}
	return false;
}

function cleanDocsFolder($professorId)
{
	$fullDir = __DIR__ . "/../../" . uploadDir . $professorId . "/docs/";
	
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

function checkForEmptyDocsDir($professorId)
{
	$fullDir = __DIR__ . "/../../" . uploadDir . $professorId . "/docs/";
	
	if (is_dir($fullDir))
		if (dir_is_empty($fullDir))
			rmdir($fullDir);
}

function checkForEmptyProfessorDir($professorId)
{
	$fullDir = __DIR__ . "/../../" . uploadDir . $professorId . "/";
	
	if (is_dir($fullDir))
		if (dir_is_empty($fullDir))
			rmdir($fullDir);
}