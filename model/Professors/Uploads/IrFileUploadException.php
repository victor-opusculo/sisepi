<?php

namespace SisEpi\Model\Professors\Uploads;

class IrFileUploadException extends \Exception
{
	public $professorId;
	
	public function __construct($errMessage, $fileName, $professorId = null)
	{
		parent::__construct($errMessage . " | Arquivo: " . $fileName);
		$this->professorId = $professorId;
	}
}