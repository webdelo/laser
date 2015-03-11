<?php
namespace modules\orders\orderGoods\lib;
class OrderGood extends \core\modules\base\ModuleDecorator
{
	use \core\traits\RequestHandler;

	function __construct($objectId)
	{
		$object = new OrderGoodObject($objectId);
		$object = new \modules\parameters\components\parametersValues\lib\ParametersValuesRelationDecorator($object);
		$object = new \modules\properties\components\propertiesValues\lib\RelationsDecorator($object);

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
		return $this->price * $this->getQuantity();
	}

	public function getQuantity()
	{
		return $this->quantity;
	}

	public function getBasePrice()
	{
		return round($this->basePrice * $this->getQuantity());
	}

	public function getIncome()
	{
		return round($this->getPrice() - $this->getBasePrice());
	}

	public function isAnOffer()
	{
		return get_class($this->getGood()) == 'modules\catalog\offers\lib\Offer';
	}

	public function isAnComplect()
	{
		return get_class($this->getGood()) == 'modules\catalog\complects\lib\Complect';
	}
}
