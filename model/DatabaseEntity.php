<?php

class DatabaseEntity
{
    protected array $schema;
	protected string $cryptoKey;
	
	public array $postData = [];

    public function __construct($modelDeclaration, $data)
    {
        $this->schema = json_decode(file_get_contents(__DIR__ . "/$modelDeclaration.model.json"), true);

        if ($data === 'new')
			$this->constrctNew();
		else if ($data == $_POST)
			$this->constructFromFormInput($data);
		else if (is_array($data))
			foreach ($data as $key => $value)
				$this->$key = $value;
    }
	
	public function setCryptoKey(string $key)
	{
		$this->cryptoKey = $key;
	}

    public function generateSQLUpdateCommandColumnsAndFields() : array
    {
        $dataSchemaFiltered = array_filter($this->schema['schema'], function($key)
        {
            return isset($this->$key) && $key !== $this->schema['PK_ColumnName'];
        }, ARRAY_FILTER_USE_KEY);

        $finalStringAsArray = [];
        $propertyCount = 0;
        foreach ($dataSchemaFiltered as $key => $value)
        {
			$finalStringAsArray[] = isset($value['encrypt']) && $value['encrypt'] ? $key . " = aes_encrypt(?, '$this->cryptoKey') " : $key . ' = ? ';
        }

        if (!is_numeric($this->{$this->schema['PK_ColumnName']})) throw new Exception("Chave primária não numérica!");

        return [ 'setColumnsAndFields' => implode(", ", $finalStringAsArray), 'whereCondition' => $this->schema['PK_ColumnName'] . " = " . $this->{$this->schema['PK_ColumnName']} ];
    }

    public function generateSQLCreateCommandColumnsAndFields() : array
    {
        $dataSchemaFiltered = array_filter($this->schema['schema'], function($key)
        {
            return isset($this->$key) && $key !== $this->schema['PK_ColumnName'];
        }, ARRAY_FILTER_USE_KEY);

        $finalColumnsArray = [];
        $finalFieldsArray = [];
        foreach ($dataSchemaFiltered as $key => $value)
        {
            $finalColumnsArray[] = $key;
            $finalFieldsArray[] = isset($value['encrypt']) && $value['encrypt'] ? "aes_encrypt(?, '$this->cryptoKey')" : "?";
        }

        return [ 'columns' => implode(", ", $finalColumnsArray), 'fields' => implode(", ", $finalFieldsArray) ];
    }

    public function generateBindParamTypesAndValues() : array
    {
        $dataSchemaFiltered = array_filter($this->schema['schema'], function($key)
        {
            return isset($this->$key) && $key !== $this->schema['PK_ColumnName'];
        }, ARRAY_FILTER_USE_KEY);

        $types = "";
        $values = [];
        foreach ($dataSchemaFiltered as $key => $value)
        {
            $types .= $value['bpType'];
            $values[] = !empty($this->$key) ? $this->$key : $value['defaultValue'];
        }

        return [ 'types' => $types, 'values' => $values ];
    }

    protected function constructFromFormInput($postData)
    {
        $dataSchema = $this->schema['schema'];

        foreach ($postData as $key => $value)
        {
            foreach ($dataSchema as $prop => $propDescriptor)
                if ($propDescriptor['formFieldName'] === $key) $this->$prop = $value;
			
				$this->postData[$key] = $value;
        }
		
    }

    protected function constructNew()
    {
        $dataSchema = $this->schema['schema'];

        foreach ($dataSchema as $prop => $propDescriptor)
        {
            $this->$prop = $propDescriptor['defaultValue'];
        }
    }
}