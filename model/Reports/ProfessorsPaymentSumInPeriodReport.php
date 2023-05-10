<?php

namespace SisEpi\Model\Reports;

use DataGridComponent;
use SisEpi\Model\Reports\Report;
use mysqli;

require_once __DIR__ . '/../../includes/common.php';
require_once __DIR__ . '/../../controller/component/DataGrid.class.php';
require_once __DIR__ . '/../../vendor/autoload.php';

final class ProfessorsPaymentSumInPeriodReport extends Report
{
    private ?array $data;
    private \DataGridComponent $dgComp;

    public function __construct(mysqli $conn, string $beginDate, string $endDate)
    {
        $getter = new \SisEpi\Model\Professors\ProfessorWorkSheet();
        $getter->setCryptKey(getCryptoKey());

        $data = $getter->getWorkSheetsInPeriod($conn, $beginDate, $endDate);

        $this->data = $this->processData($data);
        
        $dgData = \Data\transformDataRows($this->data,
        [
            'ID do docente' => fn($p) => $p['professorId'],
            'Docente' => fn($p) => $p['professorName'],
            'Eventos de que participou' => fn($p) => array_reduce($p['workSheetInfos'], fn($carry, $wsi) => $carry . ($wsi['eventName'] ? $wsi['eventName'] . ", " : ""), ""),
            'Pagamentos (bruto) somados' => fn($p) => formatDecimalToCurrency($p['totalPayment'])
        ]);

        $this->dgComp = new DataGridComponent($dgData);
    }

    public function getData() : ?array
    {
        return $this->data;
    }

    private function processData(array $data) : array
    {
        $output = [];
        foreach ($data as $workSheet)
        {
            if (!isset($output[$workSheet->professorId]))
                $output[$workSheet->professorId] = 
                [
                    'professorId' => $workSheet->professorId,
                    'professorName' => $workSheet->getOtherProperties()->professorName,
                    'totalPayment' => 0,
                    'workSheetInfos' => []
                ];

            $output[$workSheet->professorId]['workSheetInfos'][] =
            [
                'eventId' => $workSheet->eventId,
                'eventName' => $workSheet->getOtherProperties()->eventName,
                'paymentValue' => $workSheet->getPaymentValue()
            ];

            $output[$workSheet->professorId]['totalPayment']+= $workSheet->getPaymentValue();
        }

        return $output;
    }

    public function getReportItemsHTML() // returns component
    {
        yield $this->dgComp;
    }
}