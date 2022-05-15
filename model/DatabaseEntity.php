<?php

class DatabaseEntity
{
    protected array $schema;
	protected string $cryptoKey;
	
	public array $attachedData = [];

    public function __construct($modelDeclaration, $data)
    {
        $this->schema = json_decode(file_get_contents(__DIR__ . "/$modelDeclaration.model.json"), true);

        if ($data === 'new')
			$this->constructNew();
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
            $pkNotToBeSet = isset($this->schema['setPKValue']) ? 
                array_filter($this->schema['PK_ColumnName'], fn($pk) => array_search($pk, $this->schema['setPKValue']) === false )
                :
                $this->schema['PK_ColumnName'];

            return array_search($key, $pkNotToBeSet, true) === false && empty($this->schema['schema'][$key]['ignore']);
        }, ARRAY_FILTER_USE_KEY);

        $finalStringAsArray = [];
        $propertyCount = 0;
        foreach ($dataSchemaFiltered as $key => $value)
        {
			$finalStringAsArray[] = isset($value['encrypt']) && $value['encrypt'] ? $key . " = aes_encrypt(?, '$this->cryptoKey') " : $key . ' = ? ';
        }

        return [ 'setColumnsAndFields' => implode(", ", $finalStringAsArray), 'whereCondition' => $this->generateSQLCommandPrimaryKeys() ];
    }

    public function generateSQLCreateCommandColumnsAndFields() : array
    {
        $dataSchemaFiltered = array_filter($this->schema['schema'], function($key)
        {
            $pkNotToBeSet = isset($this->schema['setPKValue']) ? 
                array_filter($this->schema['PK_ColumnName'], fn($pk) => array_search($pk, $this->schema['setPKValue']) === false )
                :
                $this->schema['PK_ColumnName'];

            return array_search($key, $pkNotToBeSet, true) === false && empty($this->schema['schema'][$key]['ignore']);
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
            $pkNotToBeSet = isset($this->schema['setPKValue']) ? 
                array_filter($this->schema['PK_ColumnName'], fn($pk) => array_search($pk, $this->schema['setPKValue']) === false )
                :
                $this->schema['PK_ColumnName'];

            return array_search($key, $pkNotToBeSet, true) === false && empty($this->schema['schema'][$key]['ignore']);
        }, ARRAY_FILTER_USE_KEY);

        $types = "";
        $values = [];
        foreach ($dataSchemaFiltered as $key => $value)
        {
            $types .= $value['bpType'];
            $values[] = empty($this->$key) || !$this->verifyOnlyIfChecked($value) ? $value['defaultValue'] : $this->$key;
        }

        return [ 'types' => $types, 'values' => $values ];
    }

    protected function constructFromFormInput($postData)
    {
        $dataSchema = $this->schema['schema'];

        foreach ($postData as $key => $value)
        {
            if ($this->startsWithTableName($key))
            {
                foreach ($dataSchema as $prop => $propDescriptor)
                    if ($propDescriptor['formFieldName'] === $key) $this->$prop = $value;
            }
            else
				$this->attachedData[$key] = $value;
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

    private function verifyOnlyIfChecked($fieldObject) : bool
    {
        if (empty($fieldObject['onlyIfChecked'])) return true;
        if (empty($this->{$fieldObject['onlyIfChecked']})) return false;
        return (bool)$this->{$fieldObject['onlyIfChecked']};
    }

    private function startsWithTableName(string $formFieldName) : bool
    {
        $colonIndex = strpos($formFieldName, ":");
        return substr($formFieldName, 0, $colonIndex) === $this->schema['table'];
    }

    private function generateSQLCommandPrimaryKeys() : string
    {
        $pksColumnsFiltered = $this->mysql_escape_mimic($this->schema['PK_ColumnName']);

        $pksValuesFiltered = $this->mysql_escape_mimic(array_map( fn($pk) => $this->$pk, $pksColumnsFiltered) );

        return ' ' . implode(' AND ', array_map( fn($col, $val) => $col . ' = ' . $this->formatPrimaryKeyValue($val), $pksColumnsFiltered, $pksValuesFiltered) );
    }

    private function mysql_escape_mimic($inp)
    {
        if(is_array($inp))
            return array_map(__METHOD__, $inp);
    
        if(!empty($inp) && is_string($inp)) {
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
        }
    
        return $inp;
    }

    private function formatPrimaryKeyValue($val) : string
    {
        if (!is_string($val) && !is_numeric($val)) throw new Exception("Valor de chave primária não é string e nem inteiro.");

        return is_numeric($val) ? $val : "'$val'";
    }
}