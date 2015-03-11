<?php
namespace modules\shopcart\lib;
class ShopcartGood
{
	use traits\ShopcartBaseMethods;
	
	protected $objectClass;
	protected $objectId;
	protected $good;
	protected $quantity;

	public function __construct($objectClass, $objectId, $quantity)
	{
		$this->setObjectClass($objectClass)
			 ->setObjectId($objectId)
			 ->setQuantity($quantity)
			 ->setGood();
	}

	protected function setObjectClass($objectClass)
	{
		$this->checkObjectClass($objectClass);
		$this->objectClass = $objectClass;
		return $this;
	}

	protected function setObjectId($objectId)
	{
		$this->checkObjectId($objectId);
		$this->objectId = $objectId;
		return $this;
	}

	public function setQuantity($quantity)
	{
		try {
			$this->quantity = $quantity;
			$this->checkQuantityForGood($quantity, $this->getObject($this->objectClass, $this->objectId));
			return $this;
		} catch (ExceptionShopcart $e) {
			$this->removeShopcartGoodByKey($this->getElementKeyByGood($this));
			throw $e;
		}
	}

	protected function setGood()
	{
		$this->good = $this->getObject($this->objectClass, $this->objectId);
		return $this->checkGood();
	}

	protected function checkGood()
	{
		if ( in_array($this->goodInterface, class_implements($this->good)) )
			return $this;
		throw new \Exception('Passed object does not implement interface '.$this->goodInterface.' in class '.get_class($this).'!');
	}

	public function __call($methodName, $arguments)
	{
		if (method_exists($this, $methodName))
			return call_user_func_array(array($this, $methodName), $arguments);
		else
			return call_user_func_array(array($this->good, $methodName), $arguments);
	}

	public function __get($varName)
	{
		return (property_exists($this,$varName)) ? $this->$varName : $this->good->$varName;
	}

	public function getObjectClass()
	{
		return $this->objectClass;
	}

	public function getObjectId()
	{
		return $this->objectId;
	}

	public function getCode()
	{
		return $this->getElementKeyByGood($this);
	}

	public function getQuantity()
	{
		return $this->quantity;
	}

	public function getPrice()
	{
		return $this->good->getPriceByQuantity($this->quantity);
	}

	public function getTotalPrice()
	{
		return round($this->getPrice() * $this->quantity, 2);
	}

	public function getUrl()
	{
		$catalogItem = $this->getObject('Catalog', $this->id);
		return $catalogItem->getUrl();
	}

}