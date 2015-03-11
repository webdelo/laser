<?php
namespace modules\catalog\prices\lib;
class Prices extends \core\modules\base\ModuleDecorator implements \interfaces\IPrices
{
	function __construct($object)
	{	
		$object = new PricesObject($object);
		parent::__construct($object);
	}
	
	/* Start: IPrices interface methods */
	public function getPriceByQuantity($quantity)
	{
		return $this->getParentObject()->getPriceByQuantity($quantity);
	}
	
	public function getMinQuantity()
	{
		return $this->getParentObject()->getMinQuantity();
	}

	public function getMinPrice()
	{
		return $this->getParentObject()->getMinPrice();
	}
	/*   End: IPrices interface methods */
}