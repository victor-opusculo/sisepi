<?php

require_once __DIR__ . '/../model/database/database.php';

final class vereadormirimparents extends BaseController
{
    public function pre_home()
    {
        $this->title = "SisEPI - Vereador Mirim: Painel do Responsável";
		$this->subtitle = "Vereador Mirim: Painel do Responsável";
    }

    public function home()
    {
        require_once __DIR__ . '/../includes/vmParentLoginCheck.php';
    }

    public function pre_login()
	{
		$this->title = "SisEPI - Vereador Mirim: Log-in de Responsável";
		$this->subtitle = "Vereador Mirim: Log-in de Responsável";
	}

	public function login()
	{
		require_once __DIR__ . '/../model/vereadormirim/VmParentOtp.php';
		require_once("includes/logEngine.php");

		$operation = "";
		$wrongOTP = false;
		$otpId = null;

		if (isset($_POST['submitVmParentLogin']))
		{
			$conn = createConnectionAsEditor();
			try
			{
				$vmParentOtp = new Model\VereadorMirim\VmParentOtp();
				$vmParentOtp->setCryptKey(getCryptoKey());
				$vmParentOtp->setUp($conn, $_POST['vmParentEmail']);
				$result = $vmParentOtp->save($conn);

				if (!empty($result['newId']))
				{
					$operation = "verifyotp";
					$otpId = $result["newId"];
				}
				else
					throw new Exception("Erro ao gerar senha temporária.");
			}
			catch (Exception $e)
			{
				$this->pageMessages[] = $e->getMessage();
				writeErrorLog("No log-in de responsável de vereador mirim: " . $e->getMessage());
				$operation = "login";
			}
			finally { $conn->close(); }
		}
		else if (isset($_POST['submitVmParentOTP']))
		{
			$conn = createConnectionAsEditor();

			try
			{
				$getter = new \Model\VereadorMirim\VmParentOtp();
				$getter->id = $_POST['otpId'];
				$verifyResult = $getter->verify($conn, $_POST['givenOTP']);

				if ($verifyResult['passed'])
				{
					$parentGetter = new \Model\VereadorMirim\VmParent();
					$parentGetter->setCryptKey(getCryptoKey());
					$parentGetter->id = $verifyResult['vmParentOtp']->vmParentId;
					if ($parent = $parentGetter->getSingle($conn))
					{
						session_name("sisepi_vmparent");
						session_start();

						$_SESSION['vmparentid'] = $parent->id;
						$_SESSION['vmparentname'] = $parent->name;
						$_SESSION['vmparentemail'] = $parent->email;
						writeLog("Log-in de responsável de vereador mirim. id: $_SESSION[vmparentid]. Nome: $_SESSION[vmparentname]");

						$verifyResult['vmParentOtp']->delete($conn);

						header('location:' . URL\URLGenerator::generateSystemURL('vereadormirimparents', 'home'));
						exit();
					}
					else
						throw new Exception("Dados de responsável não localizados.");
				}
				else
				{
					$operation = "verifyotp";
					$otpId = $_POST['otpId'];
					writeErrorLog("OTP incorreta fornecida no login de responsável de verador mirim.");
					$wrongOTP = true;
				}
			}
			catch (Exception $e)
			{
				$this->pageMessages[] = $e->getMessage();
				writeErrorLog("No log-in de responsável de vereador mirim: " . $e->getMessage());
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
		session_name("sisepi_vmparent");
		session_start();

		require_once('includes/logEngine.php');
		writeLog("Log-off de docente. Nome: " . ($_SESSION['vmparentname'] ?? "") );

		session_unset();
		session_destroy();

		header('location:' . URL\URLGenerator::generateSystemURL("vereadormirimparents", "login"));
		exit();
	}
}