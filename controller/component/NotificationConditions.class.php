<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../model/exceptions.php';

use \SisEpi\Model\Notifications\UserNotificationSubscription;

abstract class NotificationConditions extends ComponentBase
{
    public function __construct(array $properties = null)
    {
        parent::__construct($properties);

        $getter = new UserNotificationSubscription();
        $getter->userId = $this->userId;
        $getter->notMod = $this->notificationModule;
        $getter->notId = $this->notificationId;

        try
        {
            $this->uNotSubscription = $getter->getSingle($this->connection);
        }
        catch (\SisEpi\Model\Exceptions\DatabaseEntityNotFound $e)
        {
            throw new Exception('Inscrição não encontrada! Certifique-se de que você está inscrito no tipo de notificação para o qual deseja definir condições.');
        }
    }

    protected $name;
    protected string $titleName;
    protected string $notificationModule;
    protected int $notificationId;
    protected mysqli $connection;
    protected int $userId;
    protected UserNotificationSubscription $uNotSubscription;

    protected abstract function getViewVars() : array;

    public function render()
    {
        $title = $this->titleName;
        $notModule = $this->notificationModule;
        $notId = $this->notificationId;
        $userId = $this->userId;
        $uNotSubscription = $this->uNotSubscription;
        $conditionsJson = $this->uNotSubscription->notConditions;

        foreach ($this->getViewVars() as $k => $v)
            $$k = $v;

        $view = $this->get_view();
		require($view);
    }
}