<?php

function isJson($value)
{
	if (is_numeric($value)) return false;
	if (is_object($value) || is_array($value)) return false;

	$obj = json_decode($value);
	if (json_last_error() === JSON_ERROR_NONE)
		return $obj;
	else
		return false;
}

function has_string_keys(array $array) 
{
	return count(array_filter(array_keys($array), 'is_string')) > 0;
}

class DataProperty implements JsonSerializable
{
	public const MYSQL_STRING = 's';
	public const MYSQL_INT = 'i';
	public const MYSQL_DOUBLE = 'd';
	public const MYSQL_BLOB = 'b';
	
	private string $dbType;
	private ?string $formFieldIdentifierName;
	private $defaultValue;
	private bool $encrypt = false;
	private $value;
	
	public function __construct($formFieldIdentifierName, $defaultValue = null, $databaseType = self::MYSQL_STRING, bool $encrypt = false)
	{
		$this->formFieldIdentifierName = $formFieldIdentifierName;
		$this->dbType = $databaseType;
		$this->defaultValue = $defaultValue;
		$this->encrypt = $encrypt;
	}
	
	public function __toString() : string
	{
		return print_r($this->value, true);
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	public function setValue($value)
	{
		$this->value = $value;
	}
	
	public function getValueForDatabase()
	{
		return $this->getValue() ?? $this->defaultValue;
	}
	
	public function setEncrypt($value)
	{
		$this->encrypt = $value;
	}
	
	public function getEncrypt()
	{
		return $this->encrypt;
	}
	
	public function getBindParamType()
	{
		return $this->dbType;
	}
	
	public function jsonSerialize()
	{
		return $this->getValue() ?? $this->defaultValue;
	}
	
	public function fillFromFormInput(array $propsFromPost)
	{
		foreach ($propsFromPost as $name => $value)
			if ($name === $this->formFieldIdentifierName)
			{
				$this->setValue($value);
				break;
			}
	}
}

class DataObjectProperty extends DataProperty implements IteratorAggregate
{
	private object $properties;
	
	public function __construct(?object $subProperties, bool $encrypt = false)
	{
		parent::__construct(null, null, DataProperty::MYSQL_STRING, $encrypt);
		$this->properties = $subProperties ?? new class {};
	}
	
	public function __toString() : string
	{
		return print_r($this->properties, true);
	}
	
	public function getIterator() : Traversable
	{
		return new ArrayIterator($this->properties);
	}
	
	public function getValue()
	{
		return $this->properties;
	}
	
	public function setValue($value)
	{
		if ($obj = isJson($value))
		{
			foreach ($obj as $p => $v)
				if (isset($this->properties->$p))
					$this->properties->$p->setValue($v);
		}
		else if (is_object($value) || (is_array($value) && has_string_keys($value)))
		{
			foreach ($value as $p => $v)
				if (isset($this->properties->$p))
					$this->properties->$p->setValue($v);
		}
		else
			throw new Exception('Erro ao definir valor de objeto do tipo DataObjectProperty. Valor não é JSON, array associativo e nem objeto.');
	}
	
	public function getValueForDatabase()
	{
		return json_encode($this->getValue());
	}
	
	public function fillFromFormInput(array $propsFromPost)
	{
		foreach ($this->properties as $prop)
			$prop->fillFromFormInput($propsFromPost);
	}
}