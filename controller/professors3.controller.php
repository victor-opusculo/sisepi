<?php

require_once "vendor/autoload.php";

use \SisEpi\Model\Database\Connection;
use SisEpi\Model\Professors\ProfessorOTP;

final class professors3 extends BaseController
{
    public function pre_editotp()
    {
        $this->title = "SisEPI - Editar OTP de docente";
		$this->subtitle = "Editar OTP de docente";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 2;
    }

    public function editotp()
    {
        $otpId = isset($_GET['id']) && Connection::isId($_GET['id']) ? $_GET['id'] : null;

        $otpObject = null;

        $conn = Connection::get();
        try
        {
            $getter = new ProfessorOTP();
            $getter->id = $otpId;
            $getter->setCryptKey(Connection::getCryptoKey());
            $otpObject = $getter->getSingle($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['otpObj'] = $otpObject;
    }

    public function pre_deleteotp()
    {
        $this->title = "SisEPI - Excluir OTP de docente";
		$this->subtitle = "Excluir OTP de docente";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 2;
    }

    public function deleteotp()
    {
        $this->editotp();
    }
}