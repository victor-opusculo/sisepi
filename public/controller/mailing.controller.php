<?php

final class mailing extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Cadastro no Mailing";
		$this->subtitle = "Cadastro no Mailing";
	}
}