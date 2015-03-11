<?php
namespace modules\catalog\complects\complectGoods\lib;
class ComplectGood extends \core\modules\base\ModuleDecorator
{
	use \core\traits\RequestHandler;

	function __construct($objectId)
	{
		$object = new ComplectGoodObject($objectId);
		parent::__construct($object);
	}

	public function remove () {
		return ($this->delete()) ? (int)$this->id : false ;
	}

	public function getGood()
	{
		return \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->goodId);
	}

	public function getPrice()
	{
		return $this->getCatalogPrice() - $this->discount;
	}

	public function getCatalogPrice()
	{
		return $this->getGood()->getPrices()->getPriceByQuantity($this->quantity)->getPrice();
	}

	public function getTotalSum()
	{
		return $this->getPrice() * $this->quantity;
	}

	public function getTotalBaseSum()
	{
		return $this->getCatalogPrice() * $this->quantity;
	}
}
