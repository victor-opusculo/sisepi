<?php
require_once("model/database/professorpanelfunctions.database.php");

final class professorpanelfunctions extends BaseController
{
    public function pre_editpersonalinfos()
    {
        $this->title = "SisEPI - Docente: Alterar dados cadastrais";
		$this->subtitle = "Docente: Alterar dados cadastrais";
    }

    public function editpersonalinfos()
    {
        require_once("includes/professorLoginCheck.php");
        require_once("model/database/generalsettings.database.php");
        require_once("model/GenericObjectFromDataRow.class.php");

        $professorObj = null;
        $consentFormFile = null;
        $consentFormVersion = null;
        $conn = createConnectionAsEditor();
        try
        {
            $professorObj = new GenericObjectFromDataRow(getSingleProfessor($_SESSION['professorid'], $conn));

            $professorObj->personalDocs = json_decode($professorObj->personalDocsJson);
            $professorObj->homeAddress = json_decode($professorObj->homeAddressJson);
            $professorObj->miniResume = json_decode($professorObj->miniResumeJson);
            $professorObj->bankData = json_decode($professorObj->bankDataJson);

            $consentFormFile = readSetting("PROFESSORS_CONSENT_FORM", $conn);
		    $consentFormVersion = readSetting("PROFESSORS_CONSENT_FORM_VERSION", $conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['professorObj'] = $professorObj;
        $this->view_PageData['consentFormFile'] = $consentFormFile;
        $this->view_PageData['consentFormVersion'] = $consentFormVersion;
    }

    public function pre_uploadpersonaldocs()
    {
        $this->title = "SisEPI - Docente: Upload de documentos";
		$this->subtitle = "Docente: Upload de documentos";
    }

    public function uploadpersonaldocs()
    {
        require_once("includes/professorLoginCheck.php");
        require_once("model/database/generalsettings.database.php");
        require_once("model/GenericObjectFromDataRow.class.php");

        $conn = createConnectionAsEditor();
        $professorDocsAttachments = null;
        $professorDocTypes = null;
        try
        {
            $professorDocsAttachments = array_map( fn($dr) => new GenericObjectFromDataRow($dr) , getUploadedPersonalDocs($_SESSION['professorid'], $conn));
            $professorDocTypes = json_decode(readSetting('PROFESSORS_DOCUMENT_TYPES', $conn), true);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['professorDocsAttachments'] = $professorDocsAttachments;
        $this->view_PageData['docTypes'] = $professorDocTypes;
    }
}