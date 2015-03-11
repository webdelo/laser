<?php
namespace modules\catalog\prices\lib;
class Price extends \core\modules\base\ModuleDecorator implements \interfaces\IPrice
{
	function __construct($objectId, $configObject)
	{
		$object = new PriceObject($objectId, $configObject);
		$object = new \modules\catalog\basePrices\lib\BasePricesDecorator($object);
		parent::__construct($object);
	}

	public function printPrice($currencySign = null)
	{
		return $this->getPrice() == 0 ? 'договорная' : $this->getPrice().' '.$currencySign;
	}

	/* Start: IPrice interface methods */
	public function getPrice()
	{
		return $this->getParentObject()->price;
	}

	public function getQuantity()
	{
		return $this->getParentObject()->quantity;
	}
	/*   End: IPrice interface methods */

	public function getOldPrice()
	{
		return $this->getParentObject()->getOldPrice();
	}

	public function getDiscount()
	{
		return $this->getParentObject()->getDiscount();
	}
}