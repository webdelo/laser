<?php
namespace modules\catalog\availability\automaticUpdates;
class AvailabiliteUpdateGood
{
	private $code;
	private $price;
	private $quantity;
	private $salePrice;
	private $name;
	private $category;

	public function __construct($code, $price, $quantity, $salePrice = null, $name = null, $category = null)
	{
		$this->setCode($code)
			 ->setPrice($price)
			 ->setQuantity($quantity)
			 ->setName($name)
			 ->setCategory($category);
	}
	
	private function setCode($code)
	{
		$code = (string)$code;
		if (empty($code))
			throw new \Exception('Empty product code in class '.get_class($this).'!');
		$this->code = $code;
		return $this;
	}
	
	private function setPrice($price)
	{
		$price = (int)$price;
		if (empty($price))
			throw new \Exception('Empty price in class '.get_class($this).'!');
		$this->price = $price;
		return $this;
	}
	
	private function setQuantity($quantity)
	{
		$quantity = (int)$quantity;
		$this->quantity = $quantity;
		return $this;
	}
	
	private function setSalePrice($salePrice)
	{
		$this->salePrice = (int)$salePrice;
		return $this;
	}
	
	private function setName($name)
	{
		$this->name = (string)$name;
		return $this;
	}
	
	private function setCategory($category)
	{
		$this->category = (string)$category;
		return $this;
	}
	
	public function getCode()
	{
		return $this->code;
	}
	
	public function getPrice()
	{
		return $this->price;
	}
	
	public function getQuantity()
	{
		return $this->quantity;
	}
	
	public function getSalePrice()
	{
		return $this->salePrice;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getCategory()
	{
		return $this->category;
	}
}