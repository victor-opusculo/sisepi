<?php

require_once "model/database/database.php";

final class vereadormirim extends BaseController
{
    public function pre_legislatures()
    {
        $this->title = "SisEPI - Vereador Mirim: Legislaturas";
		$this->subtitle = "Vereador Mirim: Legislaturas";
    }

    public function legislatures()
    {
        require_once __DIR__ . '/../../model/vereadormirim/Legislature.php';
        require_once __DIR__ . '/component/DataGrid.class.php';
        require_once __DIR__ . '/component/Paginator.class.php';

        $dataGridComponent = null;
        $paginatorComponent = null;

        $conn = createConnectionAsEditor();
        try
        {
            $legGetter = new \Model\VereadorMirim\Legislature();

            $paginatorComponent = new PaginatorComponent($legGetter->getCount($conn, $_GET['q'] ?? ''), 20);
            
            $legDrs = $legGetter->getMultiplePartially($conn,
                                                $paginatorComponent->pageNum,
                                                $paginatorComponent->numResultsOnPage,
                                                $_GET['orderBy'] ?? '',
                                                $_GET['q'] ?? '');

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($legDrs, 
            [
                'id' => fn($l) => $l->id,
                'Nome' => fn($l) => $l->name,
                'Data de início' => fn($l) => date_create($l->begin)->format('d/m/Y'),
                'Data de fim' => fn($l) => date_create($l->end)->format('d/m/Y'),
            ]));

            $dataGridComponent->columnsToHide[] = "id";
            $dataGridComponent->RudButtonsFunctionParamName = "id";
            $dataGridComponent->customButtons['Ver eleitos'] = URL\URLGenerator::generateSystemURL('vereadormirim', 'listelectedstudents', null, 'legislatureId={legId}' );
            $dataGridComponent->customButtons['Ver candidatos'] = URL\URLGenerator::generateSystemURL('vereadormirim', 'listcandidatestudents', null, 'legislatureId={legId}' );
            $dataGridComponent->customButtonsParameters['legId'] = 'id';
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['dgComp'] = $dataGridComponent;
        $this->view_PageData['pagComp'] = $paginatorComponent;
        
    }

    public function pre_listelectedstudents()
    {
        $this->title = "SisEPI - Vereador Mirim: Vereadores eleitos";
		$this->subtitle = "Vereador Mirim: Vereadores eleitos ";
    }

    public function listelectedstudents()
    {
        require_once __DIR__ . '/../../model/vereadormirim/Legislature.php';
        require_once __DIR__ . '/../../model/vereadormirim/Student.php';
        require_once __DIR__ . '/component/DataGrid.class.php';

        $legId = isset($_GET['legislatureId']) && isId($_GET['legislatureId']) ? $_GET['legislatureId'] : null;

        $legislatureObject = null;
        $dataGridComponent = null;

        $conn = createConnectionAsEditor();
        try
        {
            $legGetter = new \Model\VereadorMirim\Legislature();
            $legGetter->id = $legId;
            $legislatureObject = $legGetter->getSingle($conn);

            $stuGetter = new \Model\VereadorMirim\Student();
            $stuGetter->vmLegislatureId = $legId;
            $stuGetter->setCryptKey(getCryptoKey());
            $stud = $stuGetter->getAllElectedFromLegislature($conn);

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($stud, 
            [
                'id' => fn($s) => $s->id,
                'Nome' => fn($s) => $s->name,
                'Partido' => fn($s) => $s->getOtherProperties()->partyName ?? ''
            ]));

            $dataGridComponent->columnsToHide[] = 'id';
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('vereadormirim', 'viewstudent', '{param}');
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['legislatureObj'] = $legislatureObject;
        $this->view_PageData['dgComp'] = $dataGridComponent;
    }

    public function pre_listcandidatestudents()
    {
        $this->title = "SisEPI - Vereador Mirim: Candidatos";
		$this->subtitle = "Vereador Mirim: Candidatos ";
    }

    public function listcandidatestudents()
    {
        require_once __DIR__ . '/../../model/vereadormirim/Legislature.php';
        require_once __DIR__ . '/../../model/vereadormirim/Student.php';
        require_once __DIR__ . '/component/DataGrid.class.php';

        $legId = isset($_GET['legislatureId']) && isId($_GET['legislatureId']) ? $_GET['legislatureId'] : null;

        $legislatureObject = null;
        $dataGridComponent = null;

        $conn = createConnectionAsEditor();
        try
        {
            $legGetter = new \Model\VereadorMirim\Legislature();
            $legGetter->id = $legId;
            $legislatureObject = $legGetter->getSingle($conn);

            $stuGetter = new \Model\VereadorMirim\Student();
            $stuGetter->vmLegislatureId = $legId;
            $stuGetter->setCryptKey(getCryptoKey());
            $stud = $stuGetter->getAllCandidatesFromLegislature($conn);

            $yesIcon = new DataGridIcon('../pics/check.png', 'Sim');
            $noIcon = new DataGridIcon('../pics/wrong.png', 'Não');

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($stud, 
            [
                'id' => fn($s) => $s->id,
                'Nome' => fn($s) => $s->name,
                'Partido' => fn($s) => $s->getOtherProperties()->partyName ?? '',
                'Eleito?' => fn($s) => (bool)$s->isElected ? $yesIcon : $noIcon
            ]));

            $dataGridComponent->columnsToHide[] = 'id';
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('vereadormirim', 'viewstudent', '{param}');
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['legislatureObj'] = $legislatureObject;
        $this->view_PageData['dgComp'] = $dataGridComponent;
    }

    public function pre_viewstudent()
    {
        $this->title = "SisEPI - Vereador Mirim: Ver Vereador/Candidato";
		$this->subtitle = "Vereador Mirim: Ver Vereador/Candidato";
    }

    public function viewstudent()
    {
        require_once __DIR__ . '/../../model/vereadormirim/Student.php';
        
        $stuId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $studentObject = null;

        $conn = createConnectionAsEditor();
        try
        {
            $stuGetter = new \Model\VereadorMirim\Student();
            $stuGetter->id = $stuId;
            $stuGetter->setCryptKey(getCryptoKey());
            $studentObject = $stuGetter->getSingle($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['studentObj'] = $studentObject;
    }

    public function pre_parties()
    {
        $this->title = "SisEPI - Vereador Mirim: Partidos";
		$this->subtitle = "Vereador Mirim: Partidos";
    }

    public function parties()
    {
        require_once __DIR__ . '/../../model/vereadormirim/Party.php';
        require_once __DIR__ . '/component/DataGrid.class.php';
        require_once __DIR__ . '/component/Paginator.class.php';

        $dataGridComponent = null;
        $paginatorComponent = null;

        $conn = createConnectionAsEditor();
        try
        {
            $partyGetter = new \Model\VereadorMirim\Party();
            $paginatorComponent = new PaginatorComponent($partyGetter->getCount($conn, $_GET['q'] ?? ''), 20);
            $parties = $partyGetter->getMultiplePartially($conn, 
                                                            $paginatorComponent->pageNum,
                                                            $paginatorComponent->numResultsOnPage,
                                                            $_GET['orderBy'] ?? '',
                                                            $_GET['q'] ?? '');

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($parties, 
            [
                'id' => fn($p) => $p->id,
                'Nome' => fn($p) => $p->name . " ({$p->acronym})",
                'Número' => fn($p) => $p->number
            ]));

            $dataGridComponent->columnsToHide[] = 'id';
            $dataGridComponent->RudButtonsFunctionParamName = 'id';
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('vereadormirim', 'viewparty', '{param}');
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['dgComp'] = $dataGridComponent;
        $this->view_PageData['pagComp'] = $paginatorComponent;
    }

    public function pre_viewparty()
    {
        $this->title = "SisEPI - Vereador Mirim: Ver partido";
		$this->subtitle = "Vereador Mirim: Ver partido";
    }

    public function viewparty()
    {
        require_once __DIR__ . '/../../model/vereadormirim/Party.php';

        $partyId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

        $partyObject = null;

        $conn = createConnectionAsEditor();
        try
        {
            $partyGetter = new \Model\VereadorMirim\Party();
            $partyGetter->id = $partyId;
            $partyObject = $partyGetter->getSingle($conn);
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }

        $this->view_PageData['partyObj'] = $partyObject;
    }

    public function pre_authdocsignature()
    {
        $this->title = "SisEPI - Vereador Mirim: Verificar Assinatura";
		$this->subtitle = "Vereador Mirim: Verificar Assinatura";
    }

    public function authdocsignature()
    {
        require_once __DIR__ . "/../../model/vereadormirim/DocumentSignature.php";

        $signatureObject = null;
        $fieldLabel = null;
        $showData = false;

        if (isset($_GET['code'], $_GET['date'], $_GET['time']))
        {
            $conn = createConnectionAsEditor();
            try
            {
                $signGetter = new \Model\VereadorMirim\DocumentSignature();
                $signGetter->id = $_GET['code'];
                $signGetter->signatureDateTime = $_GET['date'] . ' ' . $_GET['time'];
                $signGetter->setCryptKey(getCryptoKey());
                $signatureObject = $signGetter->authSignature($conn);
                $signatureObject->fetchSigner($conn);

                $template = json_decode($signatureObject->getOtherProperties()->documentTemplateJson);

                foreach ($template->pages as $page)
                    foreach ($page->elements as $element)
                        if ($element->type === "generatedContent")
                            if ($element->identifier === "vmParentSignatureField" || 
                                $element->identifier === "vmStudentSignatureField" || 
                                $element->identifier === "vmSchoolSignatureField")
                                if ($element->docSignatureId === $signatureObject->docSignatureId)
                                    $fieldLabel = $element->signatureLabel ?? "Campo sem nome";
                $showData = true;
                
            }
            catch (\Model\Exceptions\FailedSignatureAuthentication $e)
            {
                $signatureObject = null;
                $showData = true;
            }
            catch (Exception $e)
            {
                $this->pageMessages[] = $e->getMessage();
                $signatureObject = null;
                $showData = false;
            }
            finally { $conn->close(); }

        }

        $this->view_PageData['signatureObj'] = $signatureObject;
        $this->view_PageData['fieldLabel'] = $fieldLabel;
        $this->view_PageData['showData'] = $showData;
    }
}