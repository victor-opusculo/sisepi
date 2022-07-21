<?php
require_once("model/database/professors2.database.php");
require_once("model/GenericObjectFromDataRow.class.php");

final class professors2 extends BaseController
{
	public function pre_workproposals()
	{
		$this->title = "SisEPI - Propostas de trabalho de docentes";
		$this->subtitle = "Propostas de trabalho de docentes";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 5;
	}

	public function workproposals()
	{
		require_once("controller/component/DataGrid.class.php");
		require_once("controller/component/Paginator.class.php");

		$paginatorComponent = null;
		$dataGridComponent = null;

		$approvedIcon = new DataGridIcon("pics/check.png", "Aprovada"); $approvedIcon->textAfterIcon = "Aprovada";
		$rejectedIcon = new DataGridIcon("pics/wrong.png", "Rejeitada"); $rejectedIcon->textAfterIcon = "Rejeitada";
		$pendingIcon = new DataGridIcon("pics/delay.png", "Pendente"); $pendingIcon->textAfterIcon = "Pendente";
		$transformDataRowsRules =
		[
			 'id' => fn($dr) => $dr['id'],
			 'Nome' => fn($dr) => truncateText($dr['name'], 60),
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
		$this->title = "SisEPI - Ver proposta de trabalho de docente";
		$this->subtitle = "Ver proposta de trabalho de docente";
		
		$this->moduleName = "PROFE";
		$this->permissionIdRequired = 5;
	}

	public function viewworkproposal()
	{
		$workProposalId = isset($_GET['id']) && isId($_GET['id']) ? $_GET['id'] : null;
		$proposalObject = null;
		try
		{
			$proposalObject = new GenericObjectFromDataRow(getSingleWorkProposal($workProposalId));
		}
		catch (Exception $e)
		{
			$this->pageMessages[] = $e->getMessage();
		}

		$this->view_PageData['proposalObj'] = $proposalObject;
	}

	public function pre_editworkproposal()
	{
		$this->title = "SisEPI - Editar proposta de trabalho de docente";
		$this->subtitle = "Editar proposta de trabalho de docente";
		
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
		$this->title = "SisEPI - Criar proposta de trabalho de docente";
		$this->subtitle = "Criar proposta de trabalho de docente";
		
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
		$this->title = "SisEPI - Excluir proposta de trabalho de docente";
		$this->subtitle = "Excluir proposta de trabalho de docente";
		
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
}