<?php
require_once __DIR__ . '/../model/database/database.php';
require_once __DIR__ . '/../includes/logEngine.php';

final class vereadormirimparentpanel extends BaseController
{
    public function pre_listvmstudents()
    {
        $this->title = "SisEPI - Vereador Mirim: Meus Candidatos/Vereadores";
		$this->subtitle = "Vereador Mirim: Meus Candidatos/Vereadores";
    }

    public function listvmstudents()
    {
        require_once __DIR__ . '/../includes/vmParentLoginCheck.php';

        require_once __DIR__ . '/../../model/vereadormirim/Student.php';
        require_once __DIR__ . '/component/DataGrid.class.php';

        $dataGridComponent = null;

        $conn = createConnectionAsEditor();
        try
        {
            $getter = new \Model\VereadorMirim\Student();
            $getter->setCryptKey(getCryptoKey());
            $getter->vmParentId = $_SESSION['vmparentid'];
            $students = $getter->getAllFromParent($conn);

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($students, 
            [
                'id' => fn($s) => $s->id,
                'Nome' => fn($s) => $s->name,
                'Legislatura' => fn($s) => $s->getOtherProperties()->legislatureName,
                'Status' => fn($s) => (bool)$s->isActive ? 'Ativo' : 'Desativado',
                'Eleito?' => fn($s) => (bool)$s->isElected ? 'Sim' : 'Não'
            ]));
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimparentpanel', 'viewvmstudent', '{param}');
            $dataGridComponent->RudButtonsFunctionParamName = 'id';
            $dataGridComponent->columnsToHide[] = 'id';
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['dgComp'] = $dataGridComponent;
    }

    public function pre_viewvmstudent()
    {
        $this->title = "SisEPI - Vereador Mirim: Ver ";
		$this->subtitle = "Vereador Mirim: Ver ";
    }

    public function viewvmstudent()
    {
        require_once __DIR__ . '/../includes/vmParentLoginCheck.php';

        require_once __DIR__ . '/../../model/vereadormirim/Student.php';
        require_once __DIR__ . '/../../model/vereadormirim/Document.php';

        require_once __DIR__ . '/component/DataGrid.class.php';

        $studentId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $vmStudentObj = null;
        $documents = null;
        $documentsDataGridComponent = null;

        $docsTransformRules = 
        [
            'ID' => fn($d) => $d->id,
            'Modelo' => fn($d) => $d->getOtherProperties()->templateName,
            'Data de assinatura' => fn($d) => date_create($d->signatureDate)->format('d/m/Y')
        ];

        $conn = createConnectionAsEditor();
        try
        {
            $getter = new \Model\VereadorMirim\Student();
            $getter->setCryptKey(getCryptoKey());
            $getter->id = $studentId;
            $vmStudentObj = $getter->getSingle($conn);

            $this->title .= (bool)$vmStudentObj->isElected ? 'Vereador' : 'Candidato';
            $this->subtitle .= (bool)$vmStudentObj->isElected ? 'Vereador' : 'Candidato';

            if ($vmStudentObj->vmParentId != $_SESSION['vmparentid'])
                throw new Exception("Erro: Tentativa de acessar perfil de vereador mirim sem vínculo com responsável.");

            $docGetter = new \Model\VereadorMirim\Document();
            $docGetter->setCryptKey(getCryptoKey());
            $docGetter->vmStudentId = $studentId;
            $documents = $docGetter->getAllFromStudent($conn);

            $documentsDataGridComponent = new DataGridComponent(Data\transformDataRows($documents, $docsTransformRules));
            $documentsDataGridComponent->RudButtonsFunctionParamName = 'ID';
            $documentsDataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimparentpanel', 'viewdocument', '{param}');
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
            $vmStudentObj = null;
        }
        finally { $conn->close(); }

        $this->view_PageData['vmStudentObj'] = $vmStudentObj;
        $this->view_PageData['vmDocumentsDgComp'] = $documentsDataGridComponent;
    }

    public function pre_viewdocument()
    {
        $this->title = "SisEPI - Vereador Mirim: Ver documento";
		$this->subtitle = "Vereador Mirim: Ver documento";
    }

    public function viewdocument()
    {
        require_once __DIR__ . '/../includes/vmParentLoginCheck.php';

        require_once __DIR__ . '/../../model/vereadormirim/DocumentInfos.php';
        require_once __DIR__ . '/../../model/vereadormirim/DocumentConditionChecker.php';

        $docId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $vmStudentObject = null;
        $vmDocumentObject = null;
        $signaturesFields = null;

        $conn = createConnectionAsEditor();
        try
        {
            $docGetter = new \Model\VereadorMirim\Document();
            $docGetter->id = $docId;
            $docGetter->setCryptKey(getCryptoKey());
            $vmDocumentObject = $docGetter->getSingle($conn);
            $vmDocumentObject->fetchSignatures($conn);

            foreach ($vmDocumentObject->signatures as $sign)
            {
                $sign->setCryptKey(getCryptoKey());
                $sign->fetchSigner($conn);
            }

            $studentGetter = new \Model\VereadorMirim\Student();
            $studentGetter->setCryptKey(getCryptoKey());
            $studentGetter->id = $vmDocumentObject->vmStudentId;
            $vmStudentObject = $studentGetter->getSingle($conn);

            if ($vmStudentObject->vmParentId != $_SESSION['vmparentid'])
                throw new Exception("Erro: Tentativa de visualizar documento de vereador mirim sem vínculo com responsável.");

            $parentGetter = new \Model\VereadorMirim\VmParent();
            $parentGetter->id = $_SESSION['vmparentid'];
            $parentGetter->setCryptKey(getCryptoKey());
            $vmParentObject = $parentGetter->getSingle($conn);

            $templateDecoded = json_decode($vmDocumentObject->getOtherProperties()->templateJson);
            $signaturesFields = [];
            $conditionChecker = new \Model\VereadorMirim\DocumentConditionChecker(new \Model\VereadorMirim\DocumentInfos($vmDocumentObject, $vmStudentObject, $vmParentObject));

            foreach ($templateDecoded->pages as $page)
                if ($conditionChecker->CheckConditions($page->conditions ?? []))
                    foreach ($page->elements as $element)
                    {
                        if ($element->type === "generatedContent")
                            if ($element->identifier === "vmParentSignatureField")
                            {
                                $signerName = array_reduce
                                (
                                    $vmDocumentObject->signatures, 
                                    fn($oldSigner, $sign) => ($sign->docSignatureId === $element->docSignatureId ? $sign->getSignerName() : null) ?? $oldSigner,
                                    null
                                );

                                $signaturesFields[] = 
                                [
                                    'docSignatureId' => $element->docSignatureId,
                                    'label' => $element->signatureLabel,
                                    'signed' => !empty($signerName)
                                ];
                            }
                    }
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
            $vmStudentObject = null;
        }
        finally { $conn->close(); }

        $this->view_PageData['vmStudentObj'] = $vmStudentObject;
        $this->view_PageData['vmDocumentObj'] = $vmDocumentObject;
        $this->view_PageData['signaturesFields'] = $signaturesFields;
    }

    public function pre_signdocument()
    {
        $this->title = "SisEPI - Vereador Mirim: Assinar documento";
		$this->subtitle = "Vereador Mirim: Assinar documento";
    }

    public function signdocument()
    {
        require_once __DIR__ . '/../includes/vmParentLoginCheck.php';
        require_once __DIR__ . '/../../model/vereadormirim/Document.php';
        require_once __DIR__ . '/../model/vereadormirim/VmParentOtp.php';
        require_once __DIR__ . '/../../model/vereadormirim/DocumentSignature.php';

        $documentId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $operation = "";
		$wrongOTP = false;
		$otpId = null;

        $getInfos = function(mysqli $conn, $documentId)
        {
            $docGetter = new \Model\VereadorMirim\Document();
            $docGetter->id = $documentId;
            $docGetter->setCryptKey(getCryptoKey());
            $documentObject = $docGetter->getSingle($conn);

            $studentGetter = new \Model\VereadorMirim\Student();
            $studentGetter->id = $documentObject->vmStudentId;
            $studentGetter->setCryptKey(getCryptoKey());
            $studentObject = $studentGetter->getSingle($conn);

            $parGetter = new \Model\VereadorMirim\VmParent();
            $parGetter->id = $studentObject->vmParentId;
            $parGetter->setCryptKey(getCryptoKey());
            $parentObject = $parGetter->getSingle($conn);

            return [ $documentObject, $studentObject, $parentObject ];
        };

        if (!isset($_POST['btnsubmitSubmitOTPforSignature'])  && isset($_POST['signatureFieldIds'], $_POST['signatureFieldNames']))
        {
            $conn = createConnectionAsEditor();
            try
            {
                [ $documentObject, $studentObject, $parentObject ] = $getInfos($conn, $documentId);

                $vmParentOtp = new \Model\VereadorMirim\VmParentOtp();
                $vmParentOtp->setCryptKey(getCryptoKey());
                $vmParentOtp->messageDefinitions = \Model\VereadorMirim\VmParentOtp::OTP_MDEF_SIGN_DOCUMENT;
                $vmParentOtp->extraMailBodyVariables = 
                [
                    'fieldNamesToSign' => array_filter($_POST['signatureFieldNames'] ?? [], fn($fieldIndex) => array_key_exists($fieldIndex, $_POST['signatureFieldIds']), ARRAY_FILTER_USE_KEY)
                ];
                $vmParentOtp->setUp($conn, $parentObject->email);
                $otpInsertResult = $vmParentOtp->save($conn);

                if ($otpInsertResult['newId'])
                {
                    $operation = "verifyotp";
                    $otpId = $otpInsertResult['newId'];
                    $this->view_PageData['documentObj'] = $documentObject;
                    $this->view_PageData['parentObj'] = $parentObject;
                }
                else 
                    throw new Exception("Erro ao gravar senha temporária.");
            }
            catch (Exception $e)
            {
                $this->pageMessages[] = $e->getMessage();
                writeErrorLog("Ao enviar OTP para assinatura de documento de vereador mirim: " . $e->getMessage());
                $operation = "error";
            }
            finally { $conn->close(); }
        }
        else if (isset($_POST['btnsubmitSubmitOTPforSignature'], $_POST['signatureFieldIds'], $_POST['signatureFieldNames']))
        {
            $conn = createConnectionAsEditor();
            try
            {
                $vmParentOtp = new \Model\VereadorMirim\VmParentOtp();
                $vmParentOtp->id = $_POST['otpId'];
                $otpObject = $vmParentOtp->getSingle($conn);
                $verifyResult = $otpObject->verify($conn, $_POST['givenOTP']);
                if ($verifyResult['passed'])
                {
                    $docGetter = new \Model\VereadorMirim\Document();
                    $docGetter->id = $documentId;
                    $docGetter->setCryptKey(getCryptoKey());
                    $documentObject = $docGetter->getSingle($conn);

                    $affectedRows = 0;
                    foreach ($_POST['signatureFieldIds'] as $id)
                    {
                        $newSignature = new \Model\VereadorMirim\DocumentSignature();
                        $newSignature->fillPropertiesFromDataRow(
                            [ 
                                'vmDocumentId' => $documentObject->id, 
                                'vmParentId' => $verifyResult['vmParentOtp']->vmParentId, 
                                'docSignatureId' => $id
                            ]);

                        if ($newSignature->verifyIfIsAlreadySigned($conn))
                            continue;

                        $affectedRows += $newSignature->save($conn)['affectedRows'];
                    }

                    if ($affectedRows > 0)
                    {
                        $verifyResult['vmParentOtp']->delete($conn);
                        $this->pageMessages[] = "Você assinou o termo/documento com sucesso!";
                        writeLog("Responsável de vereador mirim assinou documento. Documento id: {$documentObject->id}");
                        $operation = "postsign";
                    }
                    else throw new Exception("Nenhuma assinatura gravada. Isso pode ser um erro ou significar que você já assinou todos os campos selecionados.");
                }
                else
                {
                    [ $documentObject, $studentObject, $parentObject ] = $getInfos($conn, $documentId);

                    $this->view_PageData['documentObj'] = $documentObject;
                    $this->view_PageData['parentObj'] = $parentObject;

                    $operation = "verifyotp";
                    $otpId = $_POST['otpId'];
                    writeErrorLog("OTP incorreta fornecida durante assinatura de documento de vereador mirim por responsável");
                    $wrongOTP = true;
                }
            }
            catch (Exception $e)
            {
                $this->pageMessages[] = $e->getMessage();
                writeErrorLog("Ao validar OTP para assinatura de documento de vereador mirim por responsável: " . $e->getMessage());
                $operation = "error";
            }
            finally { $conn->close(); }
        }
        else if (!isset($_POST['signatureFieldIds']))
        {
            $operation = "nofieldselected";
            $this->pageMessages[] = "Nenhum campo de assinatura selecionado para assinar.";
        }

        $this->view_PageData['operation'] = $operation;
        $this->view_PageData['otpId'] = $otpId;
        $this->view_PageData['wrongOTP'] = $wrongOTP;
    }
}