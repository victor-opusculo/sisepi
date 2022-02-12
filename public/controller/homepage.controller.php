<?php

final class homepage extends BaseController
{
	public function pre_home()
	{
		$this->title = "SisEPI - Sistema de Informações da Escola do Parlamento de Itapevi";
		$this->subtitle = null;
	}
}