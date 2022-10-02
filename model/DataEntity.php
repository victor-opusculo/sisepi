<?php
require_once __DIR__ . '/SqlSelector.php';
require_once __DIR__ . '/DataProperty.php';

abstract class DataEntity implements IteratorAggregate
{	
	protected object $properties;
	protected object $otherProperties;
	
	protected array $primaryKeys = [];
	protected array $setPrimaryKeysValue = [];
	protected string $databaseTable = "";
	protected string $formFieldPrefixName = "";
	protected string $encryptionKey = "";
	
	public function getIterator() : Traversable
	{
		return new ArrayIterator($this->properties);
	}
	
	public function __get($name)
	{
		if (!isset($this->properties->$name)) return null;
		return $this->properties->$name->getValue();
	}
	
	public function __set($name, $value)
	{
		if (!isset($this->properties->$name))
			throw new Exception("Erro ao definir valor de propriedade inexistente \"$name\" em instância da classe " . self::class . '.');
		
		$this->properties->$name->setValue($value);
	}
	
	public function afterDatabaseInsert($insertResult) { return $insertResult; }
	public function beforeDatabaseInsert() { }
	
	public function afterDatabaseUpdate($updateResult) { return $updateResult; }
	public function beforeDatabaseUpdate() { }
	
	public function afterDatabaseDelete($deleteResult) { return $deleteResult; }
	public function beforeDatabaseDelete() { }
	
	public function getOtherProperties() : object
	{
		return $this->otherProperties;
	}

	public function getSingle(mysqli $conn)
	{
		$selector = $this->getGetSingleSqlSelector();
		$dataRow = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

		if (isset($dataRow)) 
			return $this->newInstanceFromDataRow($dataRow);
		else
			throw new Exception('Dados não localizados!');
	}

	public function save(mysqli $conn)
	{		
		$isUpdate = array_reduce($this->primaryKeys, fn($carry, $pk) => $carry && ($this->properties->$pk->getValue() !== null), true);
		
		$affectedRows = 0;
		$newId = null;

		if ($isUpdate)
		{
			$this->beforeDatabaseUpdate();
			
			$updateInfos = $this->getUpdateCommandInfos();
			$stmt = $conn->prepare("UPDATE {$this->databaseTable} SET $updateInfos[columnsAndFields] WHERE $updateInfos[whereClause] ");
			$stmt->bind_param($updateInfos['bindParamTypes'], ...$updateInfos['values']);
			$stmt->execute();
			$affectedRows = $stmt->affected_rows;
			$stmt->close();

			return $this->afterDatabaseUpdate( ['affectedRows' => $affectedRows] );
		}
		else
		{
			$this->beforeDatabaseInsert();
			
			$insertInfos = $this->getInsertCommandInfos();
			$stmt = $conn->prepare("INSERT INTO {$this->databaseTable} ($insertInfos[columns]) VALUES ($insertInfos[fields]) ");
			$stmt->bind_param($insertInfos['bindParamTypes'], ...$insertInfos['values']);
			$stmt->execute();
			$affectedRows = $stmt->affected_rows;
			$newId = $conn->insert_id;
			$stmt->close();
			
			return $this->afterDatabaseInsert( ['affectedRows' => $affectedRows, 'newId' => $newId] );
		}
	}

	public function delete(mysqli $conn)
	{
		$affectedRows = 0;

		$this->beforeDatabaseDelete();

		$deleteInfos = $this->getDeleteCommandInfos();
		$stmt = $conn->prepare("DELETE FROM {$this->databaseTable} WHERE $deleteInfos[whereClause] ");
		$stmt->bind_param($deleteInfos['bindParamTypes'], ...$deleteInfos['values']);
		$stmt->execute();
		$affectedRows = $stmt->affected_rows;
		$stmt->close();

		return $this->afterDatabaseDelete( ['affectedRows' => $affectedRows] );
	}
	
	public function setCryptKey(string $key)
	{
		$this->encryptionKey = $key;
	}
	
	public function fillPropertiesFromDataRow($dataRow)
	{
		$this->otherProperties = new class {};

		foreach ($dataRow as $col => $val)
		{
			if (!isset($this->properties->$col))
				$this->otherProperties->$col = $val;
			else
				$this->properties->$col->setValue($val);
		}
	}

	public function fillPropertiesFromFormInput($post)
	{
		$foundEntityProperties = [];
		$this->otherProperties = new class {};

		foreach ($post as $formFieldName => $formFieldValue)
		{
			if ($colonPos = mb_strpos($formFieldName, ':'))
			{
				$formFieldPrefixName = mb_substr($formFieldName, 0, $colonPos);
				if ($formFieldPrefixName === $this->formFieldPrefixName)
				{
					$formFieldNameIdentifier = mb_substr($formFieldName, $colonPos + 1);
					$foundEntityProperties[$formFieldNameIdentifier] = $formFieldValue;
				}
			}
			else
			{
				$this->otherProperties->$formFieldName = $formFieldValue;
			}
		}
		
		foreach ($this->properties as $prop)
			$prop->fillFromFormInput($foundEntityProperties);
	}

	protected abstract function newInstanceFromDataRow($dataRow);
	
	protected function getGetSingleSqlSelector() : SqlSelector
	{
		$selector = new SqlSelector();
		$columnsAndValues = get_object_vars($this->properties);

		$isFirstWhereClause = true;
		foreach ($columnsAndValues as $propName => $propObject)
		{
			$selector->addSelectColumn($propName);
			if (array_search($propName, $this->primaryKeys) !== null)
			{
				$selector->addWhereClause( $isFirstWhereClause ? " $propName = ? " : " AND $propName = ? " );
				$selector->addValue($propObject->getBindParamType(), $propObject->getValueForDatabase());
				$isFirstWhereClause = false;
			}
		}

		$selector->setTable($this->databaseTable);
		return $selector;
	}

	protected function getInsertCommandInfos() : array
	{
		$columnsAndValues = get_object_vars($this->properties);
		$columns = [];
		$fields = [];
		$values = [];
		$bindParamTypes = "";
		
		foreach ($this->primaryKeys as $pk)
		{
			if (array_key_exists($pk, $columnsAndValues) === true && array_search($pk, $this->setPrimaryKeysValue) === false)
				unset($columnsAndValues[$pk]);
		}
		
		foreach ($columnsAndValues as $propName => $propObject)
		{
			$columns[] = $propName;
			$fields[] = $this->getQueryField($propObject);
			$values[] = $propObject->getValueForDatabase();
			$bindParamTypes .= $propObject->getBindParamType();
		}
		
		return [ 'columns' => implode(',', $columns), 'fields' => implode(',', $fields), 'values' => $values, 'bindParamTypes' => $bindParamTypes ];
	}
	
	protected function getUpdateCommandInfos() : array
	{
		$columnsAndValues = get_object_vars($this->properties);
		$columnsAndFields = [];
		$whereClause = "";
		$values = [];
		$whereClauseValues = [];
		$bindParamTypes = "";
		$whereClauseBindParamTypes = "";
		
		$whereClauseColsAndFields = [];
		foreach ($columnsAndValues as $propName => $propObject)
		{
			$columnsAndFields[] = $propName . '=' . $this->getQueryField($propObject);
			$values[] = $propObject->getValueForDatabase();
			$bindParamTypes .= $propObject->getBindParamType();
		
			if (array_search($propName, $this->primaryKeys) !== false)
			{
				$whereClauseColsAndFields[] = $propName . '=' . $this->getQueryField($propObject);
				$whereClauseValues[] = $propObject->getValueForDatabase();
				$whereClauseBindParamTypes .= $propObject->getBindParamType();
			}
		}

		$whereClause = implode(' AND ', $whereClauseColsAndFields);
		$values = [...$values, ...$whereClauseValues];
		$bindParamTypes = $bindParamTypes . $whereClauseBindParamTypes;

		return [ 'columnsAndFields' => implode(',', $columnsAndFields), 'whereClause' => $whereClause, 'values' => $values, 'bindParamTypes' => $bindParamTypes ];
	}

	protected function getDeleteCommandInfos() : array
	{
		$columnsAndValues = get_object_vars($this->properties);
		$whereClauseColsAndFields = [];
		$values = [];
		$bindParamTypes = "";

		foreach ($columnsAndValues as $propName => $propObject)
		{
			if (array_search($propName, $this->primaryKeys) !== false)
			{
				$whereClauseColsAndFields[] = $propName . '=' . $this->getQueryField($propObject);
				$values[] = $propObject->getValueForDatabase();
				$bindParamTypes .= $propObject->getBindParamType();
			}
		}

		$whereClause = implode(' AND ', $whereClauseColsAndFields);

		return [ 'whereClause' => $whereClause, 'values' => $values, 'bindParamTypes' => $bindParamTypes ];
	}

	protected function getQueryField(object $propObject) : string
	{
		return $propObject->getEncrypt() ? "aes_encrypt(?, '{$this->encryptionKey}')" : '?';
	}
}