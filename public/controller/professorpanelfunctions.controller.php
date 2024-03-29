<?php

use SisEpi\Model\Database\Connection;
use SisEpi\Pub\Model\Professors\Professor;

require_once("model/Database/professorpanelfunctions.database.php");
require_once __DIR__ . "/../../vendor/autoload.php";

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
        require_once("model/Database/generalsettings.database.php");
        require_once("model/GenericObjectFromDataRow.class.php");

        $professorObj = null;
        $consentFormTermId = null;
        $consentFormTermInfos = null;
        $races = null;
        $conn = createConnectionAsEditor();
        try
        {
            $professorGetter = new Professor();
            $professorGetter->id = $_SESSION['professorid'];
            $professorGetter->setCryptKey(Connection::getCryptoKey());
            $professorObj = $professorGetter->getSingle($conn);

            $consentFormTermId = readSetting("PROFESSORS_CONSENT_FORM_TERM_ID", $conn);
            $consentFormTermInfos = getTermInfos($consentFormTermId, $conn);

            $racesGetter = new \SisEpi\Model\Enums\Enum();
            $racesGetter->type = 'RACE';
            $races = $racesGetter->getAllFromType($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['professorObj'] = $professorObj;
        $this->view_PageData['consentFormTermId'] = $consentFormTermId;
        $this->view_PageData['consentFormTermInfos'] = $consentFormTermInfos;
        $this->view_PageData['races'] = $races;
    }

    public function pre_uploadpersonaldocs()
    {
        $this->title = "SisEPI - Docente: Upload de documentos";
		$this->subtitle = "Docente: Upload de documentos";
    }

    public function uploadpersonaldocs()
    {
        require_once("includes/professorLoginCheck.php");
        require_once("model/Database/generalsettings.database.php");
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
        $this->title = "SisEPI - Docente: Planos de aula";
		$this->subtitle = "Docente: Planos de aula";
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
            'Tema' => fn($dr) => $dr['name'],
            'Relacionamento' => fn($dr) => $dr['ownerProfessorId'] === $_SESSION['professorid'] ? 'Você é o dono deste plano' : 'Você está vinculado a este plano',
            'Data de envio' => fn($dr) => date_create($dr['registrationDate'])->format('d/m/Y H:i:s')
        ]));
		$dataGridComponent->columnsToHide[] = "id";
        $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("professorpanelfunctions", "viewprofworkproposal", "{param}");

        $this->view_PageData['dgComp'] = $dataGridComponent;
        $this->view_PageData['pagComp'] = $paginatorComponent;
    }

    public function pre_newprofworkproposal()
    {
        $this->title = "SisEPI - Docente: Novo plano de aula";
		$this->subtitle = "Docente: Novo plano de aula";
    }

    public function newprofworkproposal()
    {
        require_once("includes/professorLoginCheck.php");
        require_once "model/Database/generalsettings.database.php";

        $odsData = null;
        try
        {
            $odsData = readSetting("ODS_DATA");
            $odsData = json_decode($odsData, false, 512, JSON_THROW_ON_ERROR);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }

		$this->view_PageData['fileAllowedMimeTypes'] = implode(",", WORK_PROPOSAL_ALLOWED_TYPES);
		$this->view_PageData['odsData'] = $odsData;
    }

    public function pre_viewprofworkproposal()
    {
        $this->title = "SisEPI - Docente: Ver plano de aula";
		$this->subtitle = "Docente: Ver plano de aula";
    }

    public function viewprofworkproposal()
    {
        require_once("includes/professorLoginCheck.php");
        require_once("controller/component/Tabs.class.php");
        require_once("model/GenericObjectFromDataRow.class.php");
        require_once("model/DatabaseEntity.php");
        require_once "model/Database/generalsettings.database.php";
        require_once(__DIR__ . "/../../includes/Professor/ProfessorWorkDocsConditionChecker.php");
        require_once(__DIR__ . "/../../includes/Professor/ProfessorDocInfos.php");

        $wpId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
        $professorWorkProposalsObject = null;
        $professorWorkSheetsObjs = null;
        $tabComponent = new TabsComponent('worksheetstabs');
        $odsData = null;
        $odsProposal = null;
        $odsProposalCodes = null;
        $conn = createConnectionAsEditor();
        try
        {
            $professorWorkProposalsObject = new GenericObjectFromDataRow(getSingleWorkProposal($_SESSION['professorid'], $wpId, $conn));
            $professorWorkProposalsObject->infosFields = json_decode($professorWorkProposalsObject->infosFields ?? '');
            $professorWorkSheetsDrs = getWorkSheets($_SESSION['professorid'], $wpId, $conn);
            $professorWorkSheetsObjs = array_map( fn($dr) => new DatabaseEntity('ProfessorWorkSheet', $dr), $professorWorkSheetsDrs);

            foreach ($professorWorkSheetsObjs as $ws)
            {
                $pdi = new \Professor\ProfessorDocInfos(new DatabaseEntity('Professor', getSingleProfessor($_SESSION['professorid'], $conn)), null, $ws);
                $condChecker = new \Professor\ProfessorWorkDocsConditionChecker($pdi);

                $ws->_signatures = getWorkDocSignatures($ws->id, $_SESSION['professorid'], $conn) ?? [];
                $docTemplate = getSingleDocTemplate($ws->professorDocTemplateId, $conn);
                $ws->_signaturesFields = [];
                if ($docTemplate)
                {
                    $docTemplate = json_decode($docTemplate['templateJson'] ?? '');
                    if (!empty($docTemplate->pages))
                        foreach ($docTemplate->pages as $pageT)
                            if ($condChecker->CheckConditions($pageT->conditions ?? []))
                                foreach ($pageT->elements as $pageElementT)
                                    if ($pageElementT->type === "generatedContent" && $pageElementT->identifier === "professorSignatureField")
                                        $ws->_signaturesFields[] = $pageElementT;    
                }    
            }

            $odsData = readSetting('ODS_DATA', $conn);
            $odsData = json_decode($odsData, false, 512, JSON_THROW_ON_ERROR);

            $odsProposalGetter = new \SisEpi\Pub\Model\Professors\ProfessorOdsProposal();
            $odsProposalGetter->professorWorkProposalId = $wpId;
            $odsProposal = $odsProposalGetter->getSingleOfWorkProposalIfExists($conn);

            if (isset($odsProposal))
                $odsProposalCodes = json_decode($odsProposal->odsGoals ?? '');
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['tabComp'] = $tabComponent;
        $this->view_PageData['workProposalObj'] = $professorWorkProposalsObject;
        $this->view_PageData['workSheetsObjs'] = $professorWorkSheetsObjs;
        $this->view_PageData['odsData'] = $odsData;
        $this->view_PageData['odsProposal'] = $odsProposal;
        $this->view_PageData['odsProposalCodes'] = $odsProposalCodes;
    }

    public function pre_editprofworkproposal()
    {
        $this->title = "SisEPI - Docente: Editar plano de aula";
		$this->subtitle = "Docente: Editar plano de aula";
    }

    public function editprofworkproposal()
    {
        require_once("includes/professorLoginCheck.php");
        require_once("model/DatabaseEntity.php");
        require_once "model/Database/generalsettings.database.php";

        $wpId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
        $professorWorkProposalsObject = null;
        $odsData = null;
        $odsProposal = null;
        $odsProposalCodes = [];
        $conn = createConnectionAsEditor();
        try
        {
            $professorWorkProposalsObject = new DatabaseEntity('ProfessorWorkProposalEditable', getSingleWorkProposal($_SESSION['professorid'], $wpId, $conn));

            if ($professorWorkProposalsObject->isApproved === 1)
                throw new Exception('Não é possível editar planos já aprovados. Caso precise realmente alterar informações ou o arquivo, entre em contato com a Escola.');

            $odsData = readSetting("ODS_DATA", $conn);
            $odsData = json_decode($odsData, false, 512, JSON_THROW_ON_ERROR);

            $odsProposalGetter = new \SisEpi\Pub\Model\Professors\ProfessorOdsProposal();
            $odsProposalGetter->professorWorkProposalId = $professorWorkProposalsObject->id;
            $odsProposal = $odsProposalGetter->getSingleOfWorkProposalIfExists($conn);

            if (isset($odsProposal))
                $odsProposalCodes = json_decode($odsProposal->odsGoals);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['proposalObj'] = $professorWorkProposalsObject;
        $this->view_PageData['fileAllowedMimeTypes'] = implode(",", WORK_PROPOSAL_ALLOWED_TYPES);
        $this->view_PageData['odsData'] = $odsData;
        $this->view_PageData['odsProposalCodes'] = $odsProposalCodes;
        $this->view_PageData['odsProposal'] = $odsProposal;
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
        require_once("model/Database/professors.database.php");
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

                        require_once __DIR__ . '/../../model/Notifications/Classes/ProfessorSignedWorkDocNotification.php';

                        $notification = new \SisEpi\Model\Notifications\Classes\ProfessorSignedWorkDocNotification
                        ([
                            'workProposalId' => $workSheetObj->professorWorkProposalId,
                            'workSheetId' => $workSheetObj->id,
                            'professorId' => $_SESSION['professorid']
                        ]);
                        $notification->push($conn);
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

    public function pre_editinssdeclaration()
    {
        $this->title = "SisEPI - Docente: Editar declaração de INSS";
		$this->subtitle = "Docente: Editar declaração de INSS";
    } 

    public function editinssdeclaration()
    {
        require_once("includes/professorLoginCheck.php");
        require_once("model/DatabaseEntity.php");

        $wsId = isset($_GET['workSheetId']) && isId($_GET['workSheetId']) ? $_GET['workSheetId'] : null;
        $workSheetObj = null;
        
        $conn = createConnectionAsEditor();
        try
        {
            $workSheetObj = new DatabaseEntity('ProfessorWorkSheet', getSingleWorkSheet($_SESSION['professorid'], $wsId, $conn));
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['workSheetObj'] = $workSheetObj;
    }

    public function pre_eventsurveysreports()
    {
        $this->title = "SisEPI - Docente: Feedback de alunos";
		$this->subtitle = "Docente: Feedback de alunos";
    }

    public function eventsurveysreports()
    {
        require_once "includes/professorLoginCheck.php";
        require_once "controller/component/DataGrid.class.php";

        $dataGridComponent = null;
        try
        {
            $eventsDrs = getEventsInWhichProfessorIsAssociated($_SESSION['professorid']) ?? [];
            $transformRules =
            [
                'id' => fn($dr) => $dr['id'],
                'Evento' => fn($dr) => $dr['name'],
                'Data de início' => fn($dr) => date_create($dr['beginDate'])->format('d/m/Y')
            ];

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($eventsDrs, $transformRules));
            $dataGridComponent->columnsToHide[] = 'id';
            $dataGridComponent->customButtons['Visualizar'] = URL\URLGenerator::generateSystemURL('professorpanelfunctions', 'singleeventsurveysreport', null, 'eventId={eventid}');
            $dataGridComponent->customButtonsParameters['eventid'] = 'id';
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }

        $this->view_PageData['dgComp'] = $dataGridComponent;
    }

    public function pre_singleeventsurveysreport()
    {
        $this->title = "SisEPI - Docente: Feedback de alunos de evento";
		$this->subtitle = "Docente: Feedback de alunos de evento";
    }

    public function singleeventsurveysreport()
    {
        require_once "includes/professorLoginCheck.php";
        require_once "model/GenericObjectFromDataRow.class.php";

        $reportObj = null;
        $eventObj = null;
        $conn = createConnectionAsEditor();
        try
        {
            if (isset($_GET['eventId']))
            {
                if (isProfessorAssociatedWithEvent($_GET['eventId'], $_SESSION['professorid'], $conn))
                {
                    $reportObj = new \SisEpi\Pub\Model\Reports\EventSurveyReport($_GET['eventId'], $conn);
                    $eventObj = new GenericObjectFromDataRow(getSingleEvent($_GET['eventId'], $conn));
                }
                else throw new Exception('Você não está associado ao evento cujo relatório está tentando abrir.');
            }
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }
        
        $this->view_PageData['reportObj'] = $reportObj;
        $this->view_PageData['eventObj'] = $eventObj;
    }

    public function pre_informerendimentos()
    {
        $this->title = "SisEPI - Docente: Informes de rendimentos";
		$this->subtitle = "Docente: Informes de rendimentos";
    }

    public function informerendimentos()
    {
        require_once "includes/professorLoginCheck.php";
        require_once "controller/component/DataGrid.class.php";

        $conn = Connection::get();
        $IrDataGrid = null;
        try
        {
            $getter = new \SisEpi\Model\Professors\ProfessorInformeRendimentosAttachment();
            $getter->professorId = $_SESSION['professorid'];
            $irAttachs = $getter->getAllFromProfessor($conn);

            if (empty($irAttachs))
                throw new Exception('Não há informes de rendimentos cadastrados.');

            $IrDataGrid = new DataGridComponent(Data\transformDataRows($irAttachs, 
			[
				'id' => fn($i) => $i->id,
				'Ano-calendário' => fn($i) => $i->year
			]));
			$IrDataGrid->RudButtonsFunctionParamName = 'id';
			$IrDataGrid->columnsToHide[] = 'id';
			$IrDataGrid->customButtons['Baixar'] = URL\URLGenerator::generateFileURL('generate/viewProfessorIrFile.php', 'id={attachId}');
            $IrDataGrid->customButtonsParameters['attachId'] = 'id';

        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['IrDgComp'] = $IrDataGrid;
    }
}