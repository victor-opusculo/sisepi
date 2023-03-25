<?php

namespace SisEpi\Model\Reports;

use DataGridComponent;
use SisEpi\Model\Reports\Report;
use mysqli;

require_once __DIR__ . '/../../includes/common.php';
require_once __DIR__ . '/../../controller/component/DataGrid.class.php';
require_once __DIR__ . '/Report.php';
require_once __DIR__ . '/../Events/EventSubscription.php';
require_once __DIR__ . '/../Database/database.php';

final class EventSubscribersHoursBySubsValueReport extends Report
{
    private array $data;
    private \DataGridComponent $dgComp;

    public function __construct(mysqli $conn, string $subsQuestionValue, string $beginDate, string $endDate)
    {
        $getter = new \SisEpi\Model\Events\EventSubscription();
        $getter->setCryptKey(getCryptoKey());

        $data = $getter->getSubscriptionsHoursByQuestionValue($conn, $subsQuestionValue, $beginDate, $endDate);

        $this->data = \Data\transformDataRows($data,
        [
            'Nome' => fn($dr) => $dr['name'],
            'E-mail' => fn($dr) => $dr['email'],
            'Eventos de que participou' => fn($dr) => $dr['eventsSubscribled'],
            'Horas acumuladas' => fn($dr) => \Data\timeStampToHours($dr['hours'])
        ]);

        $this->dgComp = new DataGridComponent($this->data);
    }

    public function getReportItemsHTML() // returns component
    {
        yield $this->dgComp;
    }
}