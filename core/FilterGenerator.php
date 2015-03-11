<?php
namespace core;
class FilterGenerator
{
	private $whereQuery = '';
	private $whereData = array();
	private $limit = '';
	private $orderBy = '';

	public function setSubquery($subquery, $data = null)
	{
		foreach (func_get_args() as $key => $value)
			$key ? $this->addWhereValue($value) : $this->addWhereSubquery($value);
		return $this;
	}
	
        public function setSubqueryArray($subquery, $data = array())
	{
                foreach($data as $value)
                    $this->addWhereValue($value); 
                $this->addWhereSubquery($subquery);
                
		return $this;
	}
	
	protected function addWhereSubquery($subquery)
	{
		$this->whereQuery .= ' '.$subquery;
		return $this;
	}
	
	protected function addWhereValue($value)
	{
                $this->whereData[] = $value;
		return $this;
	}
	
	public function setOrderBy($subquery)
	{
		$this->orderBy = $subquery;
		return $this;
	}
	
	public function setLimit($subquery)
	{
		$this->limit = $subquery;
		return $this;
	}
	
	public function getFiltersArray()
	{
		return array(
			'where' => array (
				'query' => '1=1 '.$this->whereQuery,
				'data' => $this->whereData,
			),
			'limit' => $this->limit,
			'order_by' => $this->orderBy,
		);
	}
	
	public function reset()
	{
		$this->whereQuery = '';
		$this->whereData = array();
		$this->limit = '';
		$this->orderBy = '';
		return $this;
	}
}