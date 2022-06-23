<?php
require_once("model/database/librarycollection.database.php");

final class professors extends BaseController
{
	public function pre_home()
	{
		$this->pre_register();
	}
	
	public function home()
	{
		$this->action = "register";
		$this->register();
	}
	
	public function pre_register()
	{
		$this->title = "SisEPI - Cadastro de Docentes";
		$this->subtitle = "Cadastro de Docentes";
	}
	
	public function register()
	{
		require_once("model/database/generalsettings.database.php");
		
		$conn = createConnectionAsEditor();
		$consentFormFile = readSetting("PROFESSORS_CONSENT_FORM", $conn);
		$consentFormVersion = readSetting("PROFESSORS_CONSENT_FORM_VERSION", $conn);
		$conn->close();

		$this->view_PageData['consentFormFile'] = $consentFormFile;
		$this->view_PageData['consentFormVersion'] = $consentFormVersion;
	}
}