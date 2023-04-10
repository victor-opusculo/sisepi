<?php

use SisEpi\Model\Budget\BudgetEntry;
use SisEpi\Model\Database\Connection;

require_once "vendor/autoload.php";

final class budget extends BaseController
{
    public function pre_home()
    {
        $this->title = "SisEPI - Orçamento";
		$this->subtitle = "Orçamento";

        //$this->moduleName = "BUDGT";
		//$this->permissionIdRequired = 1;
    }

    public function home()
    {
        require_once "controller/component/DataGrid.class.php";
        require_once "controller/component/Paginator.class.php";

        $year = $_GET['year'] ?? date('Y');
        $searchKeywords = $_GET['q'] ?? '';
        $fromDate = $_GET['fromDate'] ?? null;
        $toDate = $_GET['toDate'] ?? null;
        $fromValue = isset($_GET['fromValue']) && is_numeric($_GET['fromValue']) ? floatval($_GET['fromValue']) : null;
        $toValue = isset($_GET['toValue']) && is_numeric($_GET['toValue']) ? floatval($_GET['toValue']) : null;

        $paginatorComponent = null;
        $dataGridComponent = null;

        $conn = Connection::get();
        try
        {
            $getter = new BudgetEntry();
            $count = $getter->getCount($conn, (int)$year, $searchKeywords, $fromValue, $toValue, $fromDate, $toDate);

            $paginatorComponent = new PaginatorComponent($count, 20);
            
            $entries = $getter->getMultiplePartially($conn, 
                                                    (int)$year, 
                                                    $paginatorComponent->pageNum, 
                                                    $paginatorComponent->numResultsOnPage,
                                                    $_GET['orderBy'] ?? '', 
                                                    $searchKeywords,
                                                    $fromValue,
                                                    $toValue,
                                                    $fromDate,
                                                    $toDate);

            $transformRules =
            [
                'ID' => fn($e) => $e->id,
                'Tipo' => fn($e) => $e->value >= 0 ? 'Receita' : 'Despesa',
                'Data' => fn($e) => date_create($e->date)->format('d/m/Y'),
                'Categoria' => fn($e) => $e->getOtherProperties()->categoryName,
                'Valor' => fn($e) => formatDecimalToCurrency($e->getOtherProperties()->absValue),
                'Detalhes' => fn($e) => truncateText($e->details, 80)
            ];

            $dataGridComponent = new DataGridComponent(Data\transformDataRows($entries, $transformRules));
            $dataGridComponent->RudButtonsFunctionParamName = 'ID';
            $dataGridComponent->detailsButtonURL = URL\URLGenerator::generateSystemURL('budget', 'view', '{param}');
            $dataGridComponent->editButtonURL = URL\URLGenerator::generateSystemURL('budget', 'edit', '{param}');
            $dataGridComponent->deleteButtonURL = URL\URLGenerator::generateSystemURL('budget', 'delete', '{param}');
        }
        catch (Exception $e)
        {
            $this->pageMessages[] = $e->getMessage();
        }
        finally { $conn->close(); }

        $this->view_PageData['dgComp'] = $dataGridComponent;
        $this->view_PageData['pagComp'] = $paginatorComponent;
    }
}