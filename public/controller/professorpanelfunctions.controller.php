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
            $professorObj->inssCollectInfos = json_decode($professorObj->inssCollectInfosJson);

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

    public function pre_professorworkproposals()
    {
        $this->title = "SisEPI - Docente: Propostas de trabalho";
		$this->subtitle = "Docente: Propostas de trabalho";
    }

    public function professorworkproposals()
    {
        require_once("includes/professorLoginCheck.php");
        require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");

        $conn = createConnectionAsEditor();
        $paginatorComponent = new PaginatorComponent(geWorkProposalsCount(($_GET["q"] ?? ""), $_SESSION['professorid'], $conn), 20);
        $professorWorkProposalsDrs = getOwnedOrVinculatedWorkProposalsPartially($_GET['q'] ?? "", 
                                                                        $_SESSION['professorid'], 
                                                                        $paginatorComponent->pageNum,
                                                                        $paginatorComponent->numResultsOnPage,
                                                                        $conn);
        $conn->close();

        $dataGridComponent = new DataGridComponent(Data\transformDataRows($professorWorkProposalsDrs, 
        [
            'id' => fn($dr) => $dr['id'],
            'Nome' => fn($dr) => $dr['name'],
            'Relacionamento' => fn($dr) => $dr['ownerProfessorId'] === $_SESSION['professorid'] ? 'Você é o dono desta proposta' : 'Você está vinculado a esta proposta',
            'Data de envio' => fn($dr) => date_create($dr['registrationDate'])->format('d/m/Y H:i:s')
        ]));
		$dataGridComponent->columnsToHide[] = "id";
        $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("professorpanelfunctions", "viewprofworkproposal", "{param}");

        $this->view_PageData['dgComp'] = $dataGridComponent;
        $this->view_PageData['pagComp'] = $paginatorComponent;
    }

    public function pre_newprofworkproposal()
    {
        $this->title = "SisEPI - Docente: Nova proposta de trabalho";
		$this->subtitle = "Docente: Nova proposta de trabalho";
    }

    public function newprofworkproposal()
    {
        require_once("includes/professorLoginCheck.php");
        
		$this->view_PageData['fileAllowedMimeTypes'] = implode(",", WORK_PROPOSAL_ALLOWED_TYPES);
    }

    public function pre_viewprofworkproposal()
    {
        $this->title = "SisEPI - Docente: Ver proposta de trabalho";
		$this->subtitle = "Docente: Ver proposta de trabalho";
    }

    public function viewprofworkproposal()
    {
        require_once("includes/professorLoginCheck.php");
        require_once("controller/component/Tabs.class.php");
        require_once("model/GenericObjectFromDataRow.class.php");
        require_once("model/DatabaseEntity.php");
        require_once(__DIR__ . "/../../includes/Professor/ProfessorWorkDocsConditionChecker.php");
        require_once(__DIR__ . "/../../includes/Professor/ProfessorDocInfos.php");

        $wpId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
        $professorWorkProposalsObject = null;
        $professorWorkSheetsObjs = null;
        $tabComponent = new TabsComponent('worksheetstabs');
        $conn = createConnectionAsEditor();
        try
        {
            $professorWorkProposalsObject = new GenericObjectFromDataRow(getSingleWorkProposal($_SESSION['professorid'], $wpId, $conn));
            $professorWorkSheetsDrs = getWorkSheets($_SESSION['professorid'], $wpId, $conn);
            $professorWorkSheetsObjs = array_map( fn($dr) => new DatabaseEntity('ProfessorWorkSheet', $dr), $professorWorkSheetsDrs);

            foreach ($professorWorkSheetsObjs as $ws)
            {
                $pdi = new Professor\ProfessorDocInfos(new DatabaseEntity('Professor', getSingleProfessor($_SESSION['professorid'], $conn)), null, $ws);
                $condChecker = new Professor\ProfessorWorkDocsConditionChecker($pdi);

                $ws->_signatures = getWorkDocSignatures($ws->id, $_SESSION['professorid'], $conn) ?? [];
                $docTemplate = getSingleDocTemplate($ws->professorDocTemplateId, $conn);
                $ws->_signaturesFields = [];
                if ($docTemplate)
                {
                    $docTemplate = json_decode($docTemplate['templateJson']);
                    foreach ($docTemplate->pages as $pageT)
                        if ($condChecker->CheckConditions($pageT->conditions ?? []))
                            foreach ($pageT->elements as $pageElementT)
                                if ($pageElementT->type === "generatedContent" && $pageElementT->identifier === "professorSignatureField")
                                    $ws->_signaturesFields[] = $pageElementT;    
                }    
            }
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['tabComp'] = $tabComponent;
        $this->view_PageData['workProposalObj'] = $professorWorkProposalsObject;
        $this->view_PageData['workSheetsObjs'] = $professorWorkSheetsObjs;
    }

    public function pre_editprofworkproposal()
    {
        $this->title = "SisEPI - Docente: Editar proposta de trabalho";
		$this->subtitle = "Docente: Editar proposta de trabalho";
    }

    public function editprofworkproposal()
    {
        require_once("includes/professorLoginCheck.php");
        require_once("model/DatabaseEntity.php");

        $wpId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
        $professorWorkProposalsObject = null;
        $conn = createConnectionAsEditor();
        try
        {
            $professorWorkProposalsObject = new DatabaseEntity('ProfessorWorkProposalEditable', getSingleWorkProposal($_SESSION['professorid'], $wpId, $conn));

            if ($professorWorkProposalsObject->isApproved === 1)
                throw new Exception('Não é possível editar propostas já aprovadas. Caso precise realmente alterar o nome ou arquivo, entre em contato com a Escola.');
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['proposalObj'] = $professorWorkProposalsObject;
        $this->view_PageData['fileAllowedMimeTypes'] = implode(",", WORK_PROPOSAL_ALLOWED_TYPES);
    }

    public function pre_signworkdoc()
    {
        $this->title = "SisEPI - Docente: Assinar documentação de empenho";
		$this->subtitle = "Docente: Assinar documentação de empenho";
    }

    public function signworkdoc()
    {
        require_once("includes/professorLoginCheck.php");
        require_once("../includes/Mail/professorSignDocOTP.php");
        require_once("model/database/professors.database.php");
		require_once("includes/logEngine.php");
        require_once("model/DatabaseEntity.php");

        $wsId = isset($_GET['workSheetId']) && isId($_GET['workSheetId']) ? $_GET['workSheetId'] : null;

        $operation = "";
		$wrongOTP = false;
		$otpId = null;

        if (!isset($_POST['btnsubmitSubmitOTPforSignature']) && isset($_POST['signatureFieldIds'], $_POST['signatureFieldNames']))
        {
            $workSheetObj = null;
            $conn = createConnectionAsEditor();
            try
            {
                $workSheetObj = new DatabaseEntity('ProfessorWorkSheet', getSingleWorkSheet($_SESSION['professorid'], $wsId, $conn));
                $professorObj = new DatabaseEntity('Professor', getSingleProfessor($_SESSION['professorid'], $conn));

                if (isset($workSheetObj))
                {
                    invalidateProfessorOTP($professorObj->id, $conn);

                    $oneTimePassword = mt_rand(100000, 999999);
                    $insertOtpResult = insertProfessorOTP($oneTimePassword, $_SESSION['professorid'], $conn);
                    if ($insertOtpResult['isCreated'])
                    {
                        $signatureDocNames = array_filter($_POST['signatureFieldNames'] ?? [], fn($docnIndex) => array_key_exists($docnIndex, $_POST['signatureFieldIds']), ARRAY_FILTER_USE_KEY);
                        if (sendEmailProfessorOTPForSignature($oneTimePassword, $professorObj->email, $professorObj->name, $signatureDocNames))
                        {
                            $operation = "verifyotp";
                            $otpId = $insertOtpResult['newId'];
                        }
                    }
                    else
                        throw new Exception("Erro ao gerar senha temporária.");
                }
            }
            catch (Exception $e)
            {
                $this->pageMessages[] = $e->getMessage();
                writeErrorLog("Ao enviar OTP para assinatura de documentação de empenho de docente: " . $e->getMessage());
                $operation = "error";
            }
            finally { $conn->close(); }

            $this->view_PageData['workSheetObj'] = $workSheetObj;
            
        }
        else if (isset($_POST['btnsubmitSubmitOTPforSignature'], $_POST['signatureFieldIds'], $_POST['signatureFieldNames']))
        {
            $workSheetObj = null;

            $conn = createConnectionAsEditor();
            try
            {
                $workSheetObj = new DatabaseEntity('ProfessorWorkSheet', getSingleWorkSheet($_SESSION['professorid'], $wsId, $conn));
                $verifyResult = verifyProfessorOTP($_POST['otpId'], $_POST['givenOTP'], $conn);
                if ($verifyResult['passed'])
                {
                    if (insertWorkDocSignature($_POST['workSheetId'], $_SESSION['professorid'], $_POST['signatureFieldIds'], $conn))
                    {
                        $this->pageMessages[] = "Você assinou os documentos com sucesso!";
                        writeLog("Docente assinou documentação de empenho. Ficha de trabalho id: $_POST[workSheetId].");
                        $operation = 'postsign';
                    }
                    else
                        throw new Exception("Nenhuma assinatura gravada. Isso pode ser um erro ou significar que você já assinou todos os documentos selecionados.");
                }
                else
                {
                    $operation = "verifyotp";
                    $otpId = $_POST['otpId'];
					writeErrorLog("OTP incorreta fornecida durante assinatura de documentação de trabalho de docente.");
                    $wrongOTP = true;
                }
            }
            catch (Exception $e)
            {
                $this->pageMessages[] = $e->getMessage();
                writeErrorLog("Ao validar OTP para assinatura de documentação de empenho de docente: " . $e->getMessage());
                $operation = "error";
            }
            finally { $conn->close(); }

            $this->view_PageData['workSheetObj'] = $workSheetObj;
        }
        else if (!isset($_POST['signatureFieldIds']))
        {
            $operation = "nodocselected";
            $this->pageMessages[] = "Nenhum documento selecionado para assinar.";
        }
        
        $this->view_PageData['operation'] = $operation;
        $this->view_PageData['otpId'] = $otpId;
        $this->view_PageData['wrongOTP'] = $wrongOTP;

    }
}