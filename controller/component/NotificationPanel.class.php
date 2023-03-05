<?php

final class NotificationPanel extends ComponentBase
{
	protected $name = "NotificationPanel";

    private mysqli $connection;

    public function render()
    {
        $view = $this->get_view();
		require($view);
    }
}