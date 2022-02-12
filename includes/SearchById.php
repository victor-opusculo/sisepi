<?php

function isSearchById(string $searchKeywords) : bool
{
	return strpos($searchKeywords, "id:") === 0;
}

class SearchById
{
	private array $individualQueries;
	private array $idsStructured;
	private string $databaseIdColumnName;
	
	public function __construct(string $searchText, string $databaseIdColumnName = "id")
	{
		if (strpos($searchText, "id:") !== false)
		{
			$searchText = substr($searchText, 3);
		
			$this->individualQueries = $this->validateQueries(explode(",", $searchText));
			$this->idsStructured = $this->convertRangeQueriesToArray($this->individualQueries);
			
			$this->databaseIdColumnName = $databaseIdColumnName;
		}
		else
			throw new Exception("Erro: Não é uma procura por IDs.");
	}
	
	public function generateSQLWhereConditions() : string
	{
		$finalString = "";
		
		array_walk($this->idsStructured, function($idElement, $key) use (&$finalString)
		{
			if (is_numeric($idElement))
				$finalString .= "$this->databaseIdColumnName = ? ";
			else if (is_array($idElement))
				$finalString .= "($this->databaseIdColumnName >= ? AND $this->databaseIdColumnName <= ?) ";
			
			if ($key < count($this->idsStructured) - 1)
				$finalString .= "OR ";
		});
		
		return $finalString;
	}
	
	public function generateBindParamTypes() : string
	{
		$output = "";
		$values = $this->generateBindParamValues();
		$i = 0;
		while ($i++ < count($values))
			$output .= "i";
		return $output;
	}
	
	public function generateBindParamValues() : array
	{
		$finalArray = [];
		foreach ($this->idsStructured as $idElement)
		{
			if (is_numeric($idElement))
				$finalArray[] = $idElement;
			else if (is_array($idElement))
			{
				$finalArray[] = $idElement[0];
				$finalArray[] = $idElement[1];
			}
		}
		return $finalArray;
	}
	
	private function validateQueries(array $queriesInArray)
	{
		$validQueries = array_filter($queriesInArray, function($query)
		{
			return is_numeric($query) || $this->validateRangeQuery($query);
		});
		
		return $validQueries;
	}
	
	private function validateRangeQuery(string $query)
	{
		$idsInQuery = explode("-", $query);
		return isset($idsInQuery[0]) && isset($idsInQuery[1]) && count($idsInQuery) === 2 && is_numeric($idsInQuery[0]) && is_numeric($idsInQuery[1]);
	}
	
	private function convertRangeQueriesToArray(array $queriesInArray)
	{
		$outputArray = [];
		foreach ($queriesInArray as $q)
		{
			if (strpos($q, "-") === false)
				$outputArray[] = $q;
			else
			{
				$ids = explode("-", $q);
				$outputArray[] = [ $ids[0] , $ids[1] ];
			}
		}
		return $outputArray;
	}
}