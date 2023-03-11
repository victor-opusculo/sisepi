<?php

require_once __DIR__ . '/../../model/notifications/SentNotification.php';

final class NotificationPanel extends ComponentBase
{

    public function __construct(array $properties = null)
    {
        parent::__construct($properties);

        $getter = new \Model\Notifications\SentNotification();
        $getter->userId = $_SESSION['userid'];
        $this->unreadNotifications = $getter->getUnreadCount($this->connection);
        $this->lastTenNotifications = $getter->getMultiplePartially($this->connection, 1, 10, 'date', '');
    }

	protected $name = "NotificationPanel";

    protected mysqli $connection;
    protected int $unreadNotifications;
    protected array $lastTenNotifications;

    public function render() 
    {
        $view = $this->get_view();

        $unread = $this->unreadNotifications;
        $lastNots = $this->lastTenNotifications;

		require($view);
    }
}