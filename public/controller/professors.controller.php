<?php
require_once ("model/database/professors.database.php");

final class professors extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Painel de Docente";
		$this->subtitle = "Painel de Docente";
	}
	
	public function home()
	{
		require_once("includes/professorLoginCheck.php");

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

	public function pre_login()
	{
		$this->title = "SisEPI - Log-in de docente";
		$this->subtitle = "Log-in de Docente";
	}

	public function login()
	{
		require_once("../includes/Mail/professorLoginOTP.php");
		require_once("../includes/logEngine.php");

		$operation = "";
		$wrongOTP = false;
		$otpId = null;

		if (isset($_POST['submitProfessorLogin']))
		{
			$conn = createConnectionAsEditor();
			try
			{
				if (($profDataRow = getProfessorBasicInfosByEmail($_POST['professorEmail'], $conn)) !== null)
				{
					invalidateProfessorOTP($profDataRow['id'], $conn);

					$oneTimePassword = mt_rand(100000, 999999);
					$otpInsertResult = insertProfessorOTP($oneTimePassword, $profDataRow['id'], $conn);

					if ($otpInsertResult['isCreated'])
					{
						if (sendEmailProfessorOTP($oneTimePassword, $_POST['professorEmail'], $profDataRow['name']))
						{
							$operation = "verifyotp";
							$otpId = $otpInsertResult['newId'];
						}
					}
					else
						throw new Exception("Erro ao gerar senha temporária.");
				}
				else
					throw new Exception("E-mail não cadastrado. Cadastre-se como docente para ter acesso ao painel.");				
			}
			catch (Exception $e)
			{
				$this->pageMessages[] = $e->getMessage();
				writeErrorLog("No log-in de docentes: " . $e->getMessage());
				$operation = "login";
			}
			finally { $conn->close(); }
		}
		else if (isset($_POST['submitProfessorOTP']))
		{
			$conn = createConnectionAsEditor();

			try
			{
				$verifyResult = verifyProfessorOTP($_POST['otpId'], $_POST['givenOTP'], $conn);
				if ($verifyResult['passed'])
				{
					session_name("sisepi_professor");
					session_start();

					if ($professorData = getProfessorForLoginAuthentication($verifyResult['professorId'], $conn))
					{
						$_SESSION['professorid'] = $professorData['id'];
						$_SESSION['professorname'] = $professorData['name'];
						$_SESSION['professoremail'] = $professorData['email'];

						header('location:' . URL\URLGenerator::generateSystemURL('professors', 'home'));
						exit();
					}
					else
						throw new Exception("Dados de docente não localizados.");
				}
				else
				{
					$operation = "verifyotp";
					$otpId = $_POST['otpId'];
					writeErrorLog("OTP incorreta fornecida no login de docente.");
					$wrongOTP = true;
				}
			}
			catch (Exception $e)
			{
				$this->pageMessages[] = $e->getMessage();
				writeErrorLog("No log-in de docentes: " . $e->getMessage());
				$operation = "login";
			}
			finally { $conn->close(); }
		}
		else
		{
			$operation = "login";
		}
		
		$this->view_PageData['operation'] = $operation;
		$this->view_PageData['otpId'] = $otpId;
		$this->view_PageData['wrongOTP'] = $wrongOTP;
	}

	public function logout()
	{
		session_name("sisepi_professor");
		session_start();

		require_once('includes/logEngine.php');
		writeLog("Log-off de docente. Nome: " . ($_SESSION['professorname'] ?? "") );

		session_unset();
		session_destroy();

		header('location:' . URL\URLGenerator::generateSystemURL("professors", "login"));
		exit();
	}
}