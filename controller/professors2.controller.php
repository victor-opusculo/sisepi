<?php
require_once("model/Database/professors2.database.php");
require_once("model/GenericObjectFromDataRow.class.php");

final class professors2 extends BaseController
{
	public function pre_workproposals()
	{
		$this->title = "SisEPI - Planos de aula de docentes";
		$this->subtitle = "Planos de aula de docentes";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 5;
	}

	public function workproposals()
	{
		require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");

		$paginatorComponent = null;
		$dataGridComponent = null;

		$approvedIcon = new DataGridIcon("pics/check.png", "Aprovado"); $approvedIcon->textAfterIcon = "Aprovado";
		$rejectedIcon = new DataGridIcon("pics/wrong.png", "Rejeitado"); $rejectedIcon->textAfterIcon = "Rejeitado";
		$pendingIcon = new DataGridIcon("pics/delay.png", "Pendente"); $pendingIcon->textAfterIcon = "Pendente";
		$transformDataRowsRules =
		[
			 'id' => fn($dr) => $dr['id'],
			 'Tema' => fn($dr) => truncateText($dr['name'], 60),
			 'Docente dono' => fn($dr) => truncateText($dr['ownerProfessorName'], 30),
			 'Data de envio' => fn($dr) => date_create($dr['registrationDate'])->format('d/m/Y H:i:s'),
			 'Status' => fn($dr) => is_null($dr['isApproved']) ? $pendingIcon : ((bool)$dr['isApproved'] ? $approvedIcon : $rejectedIcon)
		];

		$conn = createConnectionAsEditor();
		try
		{
			$paginatorComponent = new PaginatorComponent(getWorkProposalsCount(($_GET["q"] ?? ""), $conn), 20);
			$wpDrs = getWorkProposalsPartially($_GET['q'] ?? '', $_GET['orderBy'] ?? null, $paginatorComponent->pageNum, $paginatorComponent->numResultsOnPage, $conn);
			$dataGridComponent = new DataGridComponent(Data\transformDataRows($wpDrs, $transformDataRowsRules));

			$dataGridComponent->columnsToHide[] = "id";
			$dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("professors2", "viewworkproposal", "{param}");
			$dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL("professors2", "editworkproposal", "{param}");
			$dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("professors2", "deleteworkproposal", "{param}");
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }

		$this->view_PageData['dgComp'] = $dataGridComponent;
		$this->view_PageData['pagComp'] = $paginatorComponent;
	}

	public function pre_viewworkproposal()
	{
		$this->title = "SisEPI - Ver plano de aula";
		$this->subtitle = "Ver plano de aula";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 5;
	}

	public function viewworkproposal()
	{
		require_once("controller/component/DataGrid.class.php");

		$workProposalId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
		$proposalObject = null;
		$dataGridComponent = null;
		$conn = createConnectionAsEditor();
		try
		{
			$proposalObject = new GenericObjectFromDataRow(getSingleWorkProposal($workProposalId, $conn));
			$proposalObject->infosFields = json_decode($proposalObject->infosFields ?? '');
			$workSheetsDrs = getWorkSheets($workProposalId, $conn);
			$workSheetsArray = Data\transformDataRows($workSheetsDrs, 
			[
				'id' => fn($dr) => $dr['id'],
				'Docente' => fn($dr) => $dr['professorName'],
				'Evento' => fn($dr) => $dr['eventName'],
				'Data de assinatura' => fn($dr) => date_create($dr['signatureDate'])->format('d/m/Y') 
			]);
			$dataGridComponent = new DataGridComponent($workSheetsArray);
			$dataGridComponent->columnsToHide[] = "id";
			$dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL("professors2", "viewworksheet", "{param}");
			$dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL("professors2", "editworksheet", "{param}");
			$dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL("professors2", "deleteworksheet", "{param}");
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }

		$this->view_PageData['proposalObj'] = $proposalObject;
		$this->view_PageData['dgComp'] = $dataGridComponent;
	}

	public function pre_editworkproposal()
	{
		$this->title = "SisEPI - Editar plano de aula";
		$this->subtitle = "Editar plano de aula";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 7;
	}

	public function editworkproposal()
	{
		$workProposalId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
		$proposalObject = null;
		$conn = createConnectionAsEditor();
		try
		{
			$proposalObject = new GenericObjectFromDataRow(getSingleWorkProposal($workProposalId, $conn));
			$proposalObject->infosFields = json_decode($proposalObject->infosFields);
			$professorList = getProfessorsList($conn);
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }

		$this->view_PageData['proposalObj'] = $proposalObject;
		$this->view_PageData['professorList'] = $professorList;
		$this->view_PageData['fileAllowedMimeTypes'] = implode(",", WORK_PROPOSAL_ALLOWED_TYPES);
	}

	public function pre_newworkproposal()
	{
		$this->title = "SisEPI - Criar plano de aula";
		$this->subtitle = "Criar plano de aula";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 8;
	}

	public function newworkproposal()
	{
		$conn = createConnectionAsEditor();
		try
		{
			$professorList = getProfessorsList($conn);
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }
		$this->view_PageData['professorList'] = $professorList;
		$this->view_PageData['fileAllowedMimeTypes'] = implode(",", WORK_PROPOSAL_ALLOWED_TYPES);
	}

	public function pre_deleteworkproposal()
	{
		$this->title = "SisEPI - Excluir plano de aula";
		$this->subtitle = "Excluir plano de aula";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 9;
	}

	public function deleteworkproposal()
	{
		$workProposalId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
		$proposalObject = null;
		$conn = createConnectionAsEditor();
		try
		{
			$proposalObject = new GenericObjectFromDataRow(getSingleWorkProposal($workProposalId, $conn));
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }

		$this->view_PageData['proposalObj'] = $proposalObject;
	}

	public function pre_createworksheet()
	{
		$this->title = "SisEPI - Criar ficha de trabalho de docente";
		$this->subtitle = "Criar ficha de trabalho de docente";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 10;
	}

	public function createworksheet()
	{
		require_once("model/Database/generalsettings.database.php");
		require_once("controller/component/MonthCalendar.class.php");

		$workProposalId = isset($_GET['workProposalId']) && isId($_GET['workProposalId']) ? $_GET['workProposalId'] : null;

		$conn = createConnectionAsEditor();
		$proposalObject = null;
		try
		{
			$proposalObject = new GenericObjectFromDataRow(getSingleWorkProposal($workProposalId, $conn));
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}

		$paymentInfosObj = json_decode(readSetting('PROFESSORS_TYPES_AND_PAYMENT_TABLES', $conn));
		$professorDocTemplates = getDocTemplates($conn);
		$defaultCertBgFile = readSetting('STUDENTS_CURRENT_CERTIFICATE_BG_FILE', $conn);
		$conn->close();

		$this->view_PageData['proposalObject'] = $proposalObject;
		$this->view_PageData['paymentInfosObj'] = $paymentInfosObj;
		$this->view_PageData['monthList'] = MonthCalendarComponent::generateMonthsList();
		$this->view_PageData['profDocTemplates'] = $professorDocTemplates;
		$this->view_PageData['defaultCertBgFile'] = $defaultCertBgFile;
	}

	public function pre_editworksheet()
	{
		$this->title = "SisEPI - Editar ficha de trabalho de docente";
		$this->subtitle = "Editar ficha de trabalho de docente";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 11;
	}

	public function editworksheet()
	{
		require_once("model/Database/generalsettings.database.php");
		require_once("controller/component/MonthCalendar.class.php");
		require_once("model/DatabaseEntity.php");

		$workSheetId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

		$conn = createConnectionAsEditor();
		$proposalObject = null;
		$workSheetObject = null;
		try
		{
			$workSheetObject = new DatabaseEntity('ProfessorWorkSheet', getSingleWorkSheet($workSheetId, $conn));
			$proposalObject = new GenericObjectFromDataRow(getSingleWorkProposal($workSheetObject->professorWorkProposalId, $conn));
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		$paymentInfosObj = json_decode(readSetting('PROFESSORS_TYPES_AND_PAYMENT_TABLES', $conn));
		$professorDocTemplates = getDocTemplates($conn);
		$conn->close();
	
		$this->view_PageData['proposalObject'] = $proposalObject;
		$this->view_PageData['workSheetObject'] = $workSheetObject;
		$this->view_PageData['paymentInfosObj'] = $paymentInfosObj;
		$this->view_PageData['monthList'] = MonthCalendarComponent::generateMonthsList();
		$this->view_PageData['profDocTemplates'] = $professorDocTemplates;
	}

	public function pre_viewworksheet()
	{
		$this->title = "SisEPI - Ver ficha de trabalho de docente";
		$this->subtitle = "Ver ficha de trabalho de docente";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 13;
	}

	public function viewworksheet()
	{
		require_once("model/Database/generalsettings.database.php");
		require_once("model/DatabaseEntity.php");
		require_once("model/Database/professors.database.php");
		require_once(__DIR__ . "/../includes/Professor/ProfessorWorkDocsConditionChecker.php");
        require_once(__DIR__ . "/../includes/Professor/ProfessorDocInfos.php");

		$workSheetId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

		$conn = createConnectionAsEditor();
		$proposalObject = null;
		$workSheetObject = null;
		try
		{
			$workSheetObject = new DatabaseEntity('ProfessorWorkSheet', getSingleWorkSheet($workSheetId, $conn));
			$proposalObject = new GenericObjectFromDataRow(getSingleWorkProposal($workSheetObject->professorWorkProposalId, $conn));

			$pdi = new Professor\ProfessorDocInfos(new DatabaseEntity('Professor', getSingleProfessor($workSheetObject->professorId, $conn)), null, $workSheetObject);
            $condChecker = new Professor\ProfessorWorkDocsConditionChecker($pdi);

			$workSheetObject->_signatures = array_map( fn($dr) => new GenericObjectFromDataRow($dr), getWorkDocSignatures($workSheetId, $workSheetObject->professorId, $conn) ?? []);
			
			$docTemplate = json_decode(getSingleDocTemplate($workSheetObject->professorDocTemplateId, $conn)['templateJson'] ?? '');
			$workSheetObject->_signaturesFields = [];
			if (!empty($docTemplate->pages))
				foreach ($docTemplate->pages as $pageT)
					if ($condChecker->CheckConditions($pageT->conditions ?? []))
						foreach ($pageT->elements as $pageElementT)
							if ($pageElementT->type === "generatedContent" && $pageElementT->identifier === "professorSignatureField")
								$workSheetObject->_signaturesFields[] = $pageElementT; 
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }
	
		$this->view_PageData['proposalObject'] = $proposalObject;
		$this->view_PageData['workSheetObject'] = $workSheetObject;
	}

	public function pre_deleteworksheet()
	{
		$this->title = "SisEPI - Ver ficha de trabalho de docente";
		$this->subtitle = "Ver ficha de trabalho de docente";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 12;
	}

	public function deleteworksheet()
	{
		require_once("model/DatabaseEntity.php");
		$workSheetId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;

		$conn = createConnectionAsEditor();
		$workSheetObject = null;
		try
		{
			$workSheetObject = new DatabaseEntity('ProfessorWorkSheet', getSingleWorkSheet($workSheetId, $conn));
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}
		finally { $conn->close(); }
	
		$this->view_PageData['workSheetObject'] = $workSheetObject;
	}
}