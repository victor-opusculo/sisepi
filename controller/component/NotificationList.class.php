<?php

final class NotificationList extends ComponentBase
{
	protected $name = "NotificationList";

    protected array $sentNotificationObjects;

    public function render()
    {
        $notObjs = $this->sentNotificationObjects;
        $view = $this->get_view();
		require($view);
    }
}