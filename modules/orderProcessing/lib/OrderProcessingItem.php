<?php
namespace modules\orderProcessing\lib;
class OrderProcessingItem extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new OrderProcessingItemObject($objectId);
		$object = new \modules\orderProcessing\orderProcessingGoods\lib\OrderProcessingGoodsDecorator($object);
		$object = new \modules\deliveries\lib\DeliveryDecorator($object);
		$object = new \modules\promoCodes\lib\PromoCodeDecorator($object);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \modules\modulesDomain\lib\ModuleDomainDecorator($object);
		parent::__construct($object);
	}

	public function remove ()
	{
		return $this->delete();
	}

	public function getClient()
	{
		return $this->clientId
					? \core\authorization\UserFactory::getInstance()->getUserById($this->clientId)
					: $this->getNoop();
	}

	public function getManager()
	{
		return $this->managerId
					? \core\authorization\UserFactory::getInstance()->getUserById($this->managerId)
					: $this->getNoop();
	}

	public function getTotalPrice()
	{
		return $this->getGoods()->getTotalPrice() + $this->deliveryPrice;
	}

	public function getTimeAgo()
	{
		return \core\utils\Dates::timeHMSstring(time() - \core\utils\Dates::toTimestamp($this->date));
	}

	public function getDate()
	{
		return \core\utils\Dates::toFullSimpleDate($this->date);
	}
}