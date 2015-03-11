<?php
namespace modules\orderProcessing\orderProcessingGoods\lib;
class OrderProcessingGood extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new OrderProcessingGoodObject($objectId);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \modules\parameters\components\parametersValues\lib\ParametersValuesRelationDecorator($object);
		$object = new \modules\properties\components\propertiesValues\lib\RelationsDecorator($object);
		parent::__construct($object);
	}

	public function getGood()
	{
		return $this->goodId
			? $this->getObject($this->class, $this->goodId)
			: $this->getNoop();
	}

	public function remove ()
	{
		return $this->delete();
	}

	public function getPrice()
	{
		return $this->price;
	}

	public function getBasePrice()
	{
		return $this->getGood()->getBasePriceByMinQuantity();
	}

	public function getQuantity()
	{
		return $this->quantity;
	}

	public function getTotalPrice()
	{
		return $this->getPrice() * $this->getQuantity();
	}

	public function isAnOffer()
	{
		return get_class($this->getGood()) == 'modules\catalog\offers\lib\Offer';
	}

	public function isAnComplect()
	{
		return get_class($this->getGood()) == 'modules\catalog\complects\lib\Complect';
	}

	public function getComplectGoods()
	{
		$goods = new \modules\orderProcessing\orderProcessingComplectsGoods\lib\OrderProcessingComplectsGoods($this->promoCodeDiscount);
		return $goods->setSubquery(' AND `parentId`=?d ', $this->id);
	}
}