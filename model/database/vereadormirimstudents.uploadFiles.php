<?php
namespace Model\VereadorMirim\Students\Upload;

class FileUploadException extends \Exception
{
	public $vmStudentId;

	public function __construct($errMessage, $fileName, $vmStudentId)
	{
		parent::__construct($errMessage . " | Arquivo: " . $fileName);
		$this->vmStudentId = $vmStudentId;
	}
}

const studentsUploadDir = 'uploads/vereadormirim/students/';
const studentsMaxFileSize = 1048576;

/**
 * Verifica se houve erros após o upload inspecionando o arquivo no array $_FILES.
 * @param array $fileData Entrada do array $_FILES.
 * @param int $partyId ID do traço.
 * @param float $maxSize Tamanho máximo do arquivo em bytes.
 * @param array $allowedMimeTypes Conjunto de tipos MIME aceitos. Se null, qualquer tipo de arquivo é aceito.
 * @return void
 * @throws \Model\VereadorMirim\Students\Upload\FileUploadException
 * */
function checkForUploadError($fileData, $partyId, $maxSize, $allowedMimeTypes = null)
{
	$fileName = basename($fileData["name"]);
	switch ($fileData['error'])
	{
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			throw new FileUploadException("Erro no upload: Tamanho máximo de arquivo excedido!", $fileName, $partyId); break;
		case UPLOAD_ERR_PARTIAL:
			throw new FileUploadException("Erro no upload: Arquivo com upload parcialmente feito!", $fileName, $partyId); break;
		case UPLOAD_ERR_NO_FILE:
			throw new FileUploadException("Erro no upload: Nenhum arquivo definido para o upload!", $fileName, $partyId); break;
		case UPLOAD_ERR_NO_TMP_DIR:
			throw new FileUploadException("Erro no upload: Diretório temporário de upload ausente!", $fileName, $partyId); break;
		case UPLOAD_ERR_CANT_WRITE:
			throw new FileUploadException("Erro no upload: Arquivo de upload não pôde ser gravado em disco!", $fileName, $partyId); break;
		case UPLOAD_ERR_OK: 
		default:
			break;
	}

	if ($fileData['size'] > $maxSize)
		throw new FileUploadException("Erro no upload: Arquivo acima do tamanho permitido!", $fileName, $partyId);

	if (isset($allowedMimeTypes))
		if (array_search($fileData['type'], $allowedMimeTypes) === false)
			throw new FileUploadException("Erro no upload: Arquivo em formato não suportado!", $fileName, $partyId);
}

function uploadFile($vmStudentId, $filePostData, $fileInputElementName, $fileExtension)
{
		$fullDir = __DIR__ . "/../../" . studentsUploadDir . "/";
		$fileName = "$vmStudentId.$fileExtension";
		$uploadFile = $fullDir . $fileName;
		
		checkForUploadError($filePostData[$fileInputElementName], $vmStudentId, studentsMaxFileSize, null);
		
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
			die("Arquivo anexo já existente no servidor.");
		
		return false;
}

function deleteFile($fileName)
{
	$locationFilePath = __DIR__ . "/../../" . studentsUploadDir . "/" . $fileName;
	
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

function deleteAllFiles($vmStudentId)
{
	$locationFilePath = __DIR__ . "/../../" . studentsUploadDir . "/";

	if (is_dir($locationFilePath))
	{
		$files = glob($locationFilePath . "$vmStudentId.*"); // get all file names
		
		foreach($files as $file)
		{
			if(is_file($file)) 
				unlink($file); // delete file
		}
	}
}