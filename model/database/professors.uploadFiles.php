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
define("uploadDirWorkProposals", "uploads/professors/workproposals/");

const PERSONAL_DOCUMENTS_ALLOWED_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];
const WORK_PROPOSAL_ALLOWED_TYPES = 
[
	'application/pdf', 
	'application/msword',
	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	'application/vnd.ms-powerpoint',
	'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	'application/vnd.oasis.opendocument.text'
];

/**
 * Verifica se houve erros após o upload inspecionando o arquivo no array $_FILES.
 * @param array $fileData Entrada do array $_FILES.
 * @param int $professorId ID do docente que enviou o arquivo.
 * @param float $maxSize Tamanho máximo do arquivo em bytes.
 * @param array $allowedMimeTypes Conjunto de tipos MIME aceitos. Se null, qualquer tipo de arquivo é aceito.
 * @return void
 * @throws FileUploadException
 * */
function checkForUploadError($fileData, $professorId, $maxSize, $allowedMimeTypes = null)
{
	$fileName = basename($fileData["name"]);
	switch ($fileData['error'])
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

	if ($fileData['size'] > $maxSize)
		throw new FileUploadException("Erro no upload: Arquivo acima do tamanho permitido!", $fileName, $professorId);

	if (isset($allowedMimeTypes))
		if (array_search($fileData['type'], $allowedMimeTypes) === false)
			throw new FileUploadException("Erro no upload: Arquivo em formato não suportado!", $fileName, $professorId);
}

function uploadPersonalDocFile($professorId, $filePostData, $fileInputElementName)
{
		$fullDir = __DIR__ . "/../../" . uploadDir . $professorId . "/docs/";
		$fileName = basename($filePostData[$fileInputElementName]["name"]);
		$uploadFile = $fullDir . $fileName;
	
		checkForUploadError($filePostData[$fileInputElementName], $professorId, 1887436.8 /* 1,8MB */, PERSONAL_DOCUMENTS_ALLOWED_TYPES);

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

function uploadWorkProposalFile($professorId, $workProposalId, $fileExtension, $filePostData, $fileInputElementName)
{
	$fullDir = __DIR__ . "/../../" . uploadDirWorkProposals;
	$fileName = "$workProposalId.$fileExtension";
	$uploadFile = $fullDir . $fileName;

	checkForUploadError($filePostData[$fileInputElementName], $professorId, 5242880 /* 5MB */, WORK_PROPOSAL_ALLOWED_TYPES);

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

function deleteWorkProposalFile($workProposalId, bool $ignoreIfNonExistent = false)
{
	$fullDir = __DIR__ . "/../../" . uploadDirWorkProposals;
	$fileName = "$workProposalId.*";
	$locationFilePath = $fullDir . $fileName;

	$result = array_map( "unlink", glob($locationFilePath) );
	return !$ignoreIfNonExistent ? $result[0] : true;
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