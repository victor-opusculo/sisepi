<?php

#[AllowDynamicProperties]
class GenericObjectFromDataRow
{
	public function __construct($dataRow)
	{
		if ($dataRow === null) throw new Exception("Não é possível instanciar a classe 'GenericObjectFromDataRow'. dataRow nulo.");
		
		foreach($dataRow as $column => $value)
		{
			$this->$column = $value;
		}
	}
}