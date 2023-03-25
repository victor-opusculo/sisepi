<?php

require_once "model/database/database.php";
require_once "model/vereadormirim/Student.php";
require_once 'model/vereadormirim/DocumentTemplate.php';
require_once 'model/vereadormirim/Document.php';

final class vereadormirimstudents extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Vereador Mirim: Vereadores/Candidatos";
		$this->subtitle = "Vereador Mirim: Vereadores/Candidatos";
		
		$this->moduleName = "VMSTU";
		$this->permissionIdRequired = 1;
	}
	
	public function home()
	{
		require_once "controller/component/DataGrid.class.php";
		require_once "controller/component/Paginator.class.php";

		$conn = createConnectionAsEditor();
        $getter = new \SisEpi\Model\VereadorMirim\Student();
        $getter->setCryptKey(getCryptoKey());

        $piecesCount = 0;
        $paginatorComponent = null; 
        $dataGridComponent = null;

        try
        {

            $piecesCount = $getter->getCount($conn, $_GET['q'] ?? '');
            $paginatorComponent = new PaginatorComponent($piecesCount, 20);
            
            $vmStudents = $getter->getMultiplePartially($conn, 
                                                    $paginatorComponent->pageNum,
                                                    $paginatorComponent->numResultsOnPage,
                                                    $_GET['orderBy'] ?? '',
                                                    $_GET['q'] ?? '',
                                                    $_GET['legislatureId'] ?? null);
            $transformRules =
            [
                'id' => fn($s) => $s->id,
                'Nome' => fn($s) => $s->name,
                'E-mail' => fn($s) => $s->email,
                'Legislatura' => fn($s) => $s->getOtherProperties()->legislatureName,
                'Status' => fn($s) => (bool)$s->isActive ? 'Ativo' : 'Desativado',
                'Eleito?' => fn($s) => (bool)$s->isElected ? 'Sim' : 'Não'
            ];

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($vmStudents, $transformRules));
            $dataGridComponent->columnsToHide[] = 'id';
            $dataGridComponent->RudButtonsFunctionParamName = 'id';
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimstudents', 'view', '{param}');
            $dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimstudents', 'edit', '{param}');
            $dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimstudents', 'delete', '{param}');
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }
		
		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
	}

    public function pre_create()
	{
		$this->title = "SisEPI - Vereador Mirim: Criar vereador";
		$this->subtitle = "Vereador Mirim: Criar vereador";
		
		$this->moduleName = "VMSTU";
		$this->permissionIdRequired = 2;
	}

    public function create() 
    {
        require_once "model/vereadormirim/Party.php";

        $conn = createConnectionAsEditor();
        $partiesList = null;
        try
        {
            $partiesGetter = new \SisEpi\Model\VereadorMirim\Party();
            $partiesList = $partiesGetter->getAllBasic($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['partiesList'] = $partiesList;
    }

    public function pre_view()
    {
        $this->title = "SisEPI - Vereador Mirim: Ver vereador";
		$this->subtitle = "Vereador Mirim: Ver vereador";
		
		$this->moduleName = "VMSTU";
		$this->permissionIdRequired = 1;
    }

    public function view()
    {
        require_once __DIR__ . '/component/DataGrid.class.php';

        $studentId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $vmStudentObject = null;
        $documents = null;
        $documentsDataGridComponent = null;
        $conn = createConnectionAsEditor();

        $docsTransformRules = 
        [
            'ID' => fn($d) => $d->id,
            'Modelo' => fn($d) => $d->getOtherProperties()->templateName,
            'Data de assinatura' => fn($d) => date_create($d->signatureDate)->format('d/m/Y')
        ];

        try
        {
            $getter = new \SisEpi\Model\VereadorMirim\Student();
            $getter->setCryptKey(getCryptoKey());
            $getter->id = $studentId;
            $vmStudentObject = $getter->getSingle($conn);

            $docGetter = new \SisEpi\Model\VereadorMirim\Document();
            $docGetter->setCryptKey(getCryptoKey());
            $docGetter->vmStudentId = $studentId;
            $documents = $docGetter->getAllFromStudent($conn);

            $documentsDataGridComponent = new DataGridComponent(Data\transformDataRows($documents, $docsTransformRules));
            $documentsDataGridComponent->RudButtonsFunctionParamName = 'ID';
            $documentsDataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimstudents', 'viewdocument', '{param}');
            $documentsDataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL('vereadormirimstudents', 'deletedocument', '{param}');
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['vmStudentObj'] = $vmStudentObject;
        $this->view_PageData['vmDocumentsObj'] = $documents;
        $this->view_PageData['docsDgComp'] = $documentsDataGridComponent;
    }

    public function pre_edit()
    {
        $this->title = "SisEPI - Vereador Mirim: Editar vereador";
		$this->subtitle = "Vereador Mirim: Editar vereador";
		
		$this->moduleName = "VMSTU";
		$this->permissionIdRequired = 3;
    }

    public function edit()
    {
        require_once "model/vereadormirim/Party.php";

        $studentId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $vmStudentObject = null;
        $partiesList = null;
        $conn = createConnectionAsEditor();

        try
        {
            $getter = new \SisEpi\Model\VereadorMirim\Student();
            $getter->setCryptKey(getCryptoKey());
            $getter->id = $studentId;
            $vmStudentObject = $getter->getSingle($conn);

            $partiesGetter = new \SisEpi\Model\VereadorMirim\Party();
            $partiesList = $partiesGetter->getAllBasic($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['vmStudentObj'] = $vmStudentObject;
        $this->view_PageData['partiesList'] = $partiesList;
    }

    public function pre_delete()
    {
        $this->title = "SisEPI - Vereador Mirim: Excluir vereador";
		$this->subtitle = "Vereador Mirim: Excluir vereador";
		
		$this->moduleName = "VMSTU";
		$this->permissionIdRequired = 4;
    }

    public function delete()
    {
        $this->view();
    }

    public function pre_createdocument()
    {
        $this->title = "SisEPI - Vereador Mirim: Criar documento";
		$this->subtitle = "Vereador Mirim: Criar documento";
		
		$this->moduleName = "VMSTU";
		$this->permissionIdRequired = 3;
    }

    public function createdocument()
    {
        $vmDocumentTemplates = null;
        $vmStudentObject = null;

        $studentId = isset($_GET['vmStudentId']) && isId($_GET['vmStudentId']) ? $_GET['vmStudentId'] : null;

        $conn = createConnectionAsEditor();
        try
        {
            $getter = new \SisEpi\Model\VereadorMirim\Student();
            $getter->setCryptKey(getCryptoKey());
            $getter->id = $studentId;
            $vmStudentObject = $getter->getSingle($conn);

            $docGetter = new \SisEpi\Model\VereadorMirim\DocumentTemplate();
            $vmDocumentTemplates = $docGetter->getAll($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['vmDocumentTemplates'] = $vmDocumentTemplates;
        $this->view_PageData['vmStudentObj'] = $vmStudentObject;

    }

    public function pre_viewdocument()
    {
        $this->title = "SisEPI - Vereador Mirim: Ver documento";
		$this->subtitle = "Vereador Mirim: Ver documento";
		
		$this->moduleName = "VMSTU";
		$this->permissionIdRequired = 1;
    }

    public function viewdocument()
    {
        require_once __DIR__ . '/../model/vereadormirim/DocumentConditionChecker.php';
        
        $docId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $vmStudentObject = null;
        $vmDocumentObject = null;
        $signaturesFields = [];

        $conn = createConnectionAsEditor();
        try
        {
            $docGetter = new \SisEpi\Model\VereadorMirim\Document();
            $docGetter->id = $docId;
            $docGetter->setCryptKey(getCryptoKey());
            $vmDocumentObject = $docGetter->getSingle($conn);
            $vmDocumentObject->fetchSignatures($conn);

            foreach ($vmDocumentObject->signatures as $sign)
            {
                $sign->setCryptKey(getCryptoKey());
                $sign->fetchSigner($conn);
            }

            $studentGetter = new \SisEpi\Model\VereadorMirim\Student();
            $studentGetter->setCryptKey(getCryptoKey());
            $studentGetter->id = $vmDocumentObject->vmStudentId;
            $vmStudentObject = $studentGetter->getSingle($conn);

            $parentGetter = new \SisEpi\Model\VereadorMirim\VmParent();
            $parentGetter->id = $vmStudentObject->vmParentId;
            $parentGetter->setCryptKey(getCryptoKey());
            $vmParentObject = $parentGetter->getSingle($conn);

            $schoolGetter = new \SisEpi\Model\VereadorMirim\School();
            $schoolGetter->id = $vmStudentObject->vmSchoolId;
            $schoolGetter->setCryptKey(getCryptoKey());
            $vmSchoolObject = $schoolGetter->getSingle($conn);

            $conditionChecker = new \SisEpi\Model\VereadorMirim\DocumentConditionChecker(new \SisEpi\Model\VereadorMirim\DocumentInfos($vmDocumentObject, $vmStudentObject, $vmParentObject, $vmSchoolObject));

            $templateDecoded = json_decode($vmDocumentObject->getOtherProperties()->templateJson);

            $getWhoSigns = function($elementIdentifier)
            {
                switch ($elementIdentifier)
                {
                    case "vmParentSignatureField": return "Pai/Responsável";
                    case "vmStudentSignatureField": return "Vereador mirim";
                    case "vmSchoolSignatureField": return "Escola";
                }
            };

            foreach ($templateDecoded->pages as $page)
                if ($conditionChecker->CheckConditions($page->conditions ?? []))
                    foreach ($page->elements as $element)
                    {
                        if ($element->type === "generatedContent")
                            if ($element->identifier === "vmParentSignatureField" || $element->identifier === "vmStudentSignatureField" || $element->identifier === "vmSchoolSignatureField")
                            {
                                $signerName = array_reduce
                                (
                                    $vmDocumentObject->signatures, 
                                    fn($oldSigner, $sign) => ($sign->docSignatureId === $element->docSignatureId ? $sign->getSignerName() : null) ?? $oldSigner,
                                    null
                                );

                                $signaturesFields[] = 
                                [
                                    'ID do campo' => $element->docSignatureId,
                                    'Nome do campo' => hsc($element->signatureLabel),
                                    'Tipo de signatário' => hsc($getWhoSigns($element->identifier)),
                                    'Status' => !empty($signerName) ? '<span style="color:green;">Assinado</span>' : '<span style="color:red;">Não assinado</span>',
                                    'Signatário' => hsc($signerName) 
                                ];
                            }
                    }

        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['vmStudentObj'] = $vmStudentObject;
        $this->view_PageData['vmDocumentObj'] = $vmDocumentObject;
        $this->view_PageData['signaturesFields'] = $signaturesFields;
    }

    public function pre_deletedocument()
    {
        $this->title = "SisEPI - Vereador Mirim: Excluir documento";
		$this->subtitle = "Vereador Mirim: Excluir documento";
		
		$this->moduleName = "VMSTU";
		$this->permissionIdRequired = 3;
    }

    public function deletedocument()
    {
        $docId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $vmStudentObject = null;
        $vmDocumentObject = null;

        $conn = createConnectionAsEditor();
        try
        {
            $docGetter = new \SisEpi\Model\VereadorMirim\Document();
            $docGetter->id = $docId;
            $docGetter->setCryptKey(getCryptoKey());
            $vmDocumentObject = $docGetter->getSingle($conn);

            $studentGetter = new \SisEpi\Model\VereadorMirim\Student();
            $studentGetter->setCryptKey(getCryptoKey());
            $studentGetter->id = $vmDocumentObject->vmStudentId;
            $vmStudentObject = $studentGetter->getSingle($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['vmStudentObj'] = $vmStudentObject;
        $this->view_PageData['vmDocumentObj'] = $vmDocumentObject;
    }
}