<?php
// Requires: \core\traits\RequestHandler
namespace core\traits\controllers;
trait RequestLevels
{
	protected function isNumberRequestLevel($number)
	{
		$number = (int)$number - 1;
		return (isset($this->getREQUEST()[$number]) && !isset($this->getREQUEST()[$number+1]));
	}
	
	protected function isZeroRequestLevel()
	{
		return !isset($this->getREQUEST()[0]);
	}
	
	protected function isFirstRequestLevel()
	{
		return $this->isNumberRequestLevel(1);
	}
	
	protected function isSecondRequestLevel()
	{
		return $this->isNumberRequestLevel(2);
	}
	
	protected function isThirdRequestLevel()
	{
		return $this->isNumberRequestLevel(3);
	}
	
	protected function getElementFromTheEndOfRequest($position)
	{
		$return = array_keys($this->getREQUEST()->getArray());
		$return = $return[sizeof($return)-$position];
		return $this->getREQUEST()[$return];
	}

	protected function getLastElementFromRequest()
	{
		return $this->getElementFromTheEndOfRequest(1);
	}
}