<?php

class TermFileUploadException extends Exception
{
	public $termId;
	
	public function __construct($errMessage, $fileName, $termId = null)
	{
		parent::__construct($errMessage . " | Arquivo: " . $fileName);
		$this->termId = $termId;
	}
}

define("termUploadDir", "uploads/terms/");

/**
 * Verifica se houve erros após o upload inspecionando o arquivo no array $_FILES.
 * @param array $fileData Entrada do array $_FILES.
 * @param int $professorId ID do docente que enviou o arquivo.
 * @param float $maxSize Tamanho máximo do arquivo em bytes.
 * @param array $allowedMimeTypes Conjunto de tipos MIME aceitos. Se null, qualquer tipo de arquivo é aceito.
 * @return void
 * @throws FileUploadException
 * */
function checkForTermUploadError($fileData, $termId, $maxSize)
{
	$fileName = basename($fileData["name"]);
	switch ($fileData['error'])
	{
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			throw new TermFileUploadException("Erro no upload: Tamanho máximo de arquivo excedido!", $fileName, $termId); break;
		case UPLOAD_ERR_PARTIAL:
			throw new TermFileUploadException("Erro no upload: Arquivo com upload parcialmente feito!", $fileName, $termId); break;
		case UPLOAD_ERR_NO_FILE:
			throw new TermFileUploadException("Erro no upload: Nenhum arquivo definido para o upload!", $fileName, $termId); break;
		case UPLOAD_ERR_NO_TMP_DIR:
			throw new TermFileUploadException("Erro no upload: Diretório temporário de upload ausente!", $fileName, $termId); break;
		case UPLOAD_ERR_CANT_WRITE:
			throw new TermFileUploadException("Erro no upload: Arquivo de upload não pôde ser gravado em disco!", $fileName, $termId); break;
		case UPLOAD_ERR_OK: 
		default:
			break;
	}

	if ($fileData['size'] > $maxSize)
		throw new TermFileUploadException("Erro no upload: Arquivo acima do tamanho permitido!", $fileName, $termId);

	if ($fileData['type'] !== 'application/pdf')
		throw new TermFileUploadException("Erro no upload: Arquivo em formato não suportado! Deve ser PDF.", $fileName, $termId);
}

function uploadTermFile($termId, $filePostData, $fileInputElementName)
{
	$fullDir = __DIR__ . "/../../" . termUploadDir;
	$fileName = "$termId.pdf";
	$uploadFile = $fullDir . $fileName;

	checkForTermUploadError($filePostData[$fileInputElementName], $termId, 5242880 /* 5MB */);

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

function deleteTermFile($termId)
{
	$locationFilePath = __DIR__ . "/../../" . termUploadDir . $termId . ".pdf";
	
	if (file_exists($locationFilePath))
	{
		if(unlink($locationFilePath))
			return true;
		else
			die("Erro ao excluir o arquivo de termo.");
	}
	return false;
}