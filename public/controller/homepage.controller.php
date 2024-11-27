<?php

final class homepage extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Sistema de Informações EPI";
		$this->subtitle = null;
	}
}
