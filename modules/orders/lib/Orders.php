<?php
namespace modules\orders\lib;
class Orders extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new OrdersObject();
		$object = new \core\modules\filesUploaded\FilesDecorator($object);
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \modules\paymentStatuses\PaymentStatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		parent::__construct($object);
	}

	public function getOrdersByIds($idString)
	{
		$this->setSubquery('AND `id` IN (' . $idString . ')')
			->setOrderBy('`date` DESC, `id` DESC');
		return $this;
	}

	public function getTotalSum()
	{
		$return = 0;
		foreach($this as $order)
			$return += $order->getTotalSum();
		return $return;
	}

	public function getTotalBaseSum()
	{
		$return = 0;
		foreach($this as $order)
			$return += $order->getTotalBaseSum();
		return $return;
	}

	public function getIncomeWithoutCashRate()
	{
		$return = 0;
		foreach($this as $order)
			$return += $order->getIncomeWithoutCashRate();
		return $return;
	}

	public function getCashRatePrice()
	{
		$return = 0;
		foreach($this as $order)
			$return += $order->getCashRatePrice();
		return $return;
	}

	public function getIncome()
	{
		$return = 0;
		foreach($this as $order)
			$return += $order->getIncome();
		return $return;
	}

	public function getTotalGoodsSum()
	{
		$return = 0;
		foreach($this as $order)
			$return += $order->getOrderGoods()->getTotalGoodsSum();
		return $return;
	}

	public function getTotalGoodsBaseSum()
	{
		$return = 0;
		foreach($this as $order)
			$return += $order->getOrderGoods()->getTotalGoodsBaseSum();
		return $return;
	}

	public function getTotalDeliverySum()
	{
		$return = 0;
		foreach($this as $order)
			$return += $order->deliveryPrice;
		return $return;
	}

	public function getTotalDeliveryBaseSum()
	{
		$return = 0;
		foreach($this as $order)
			$return += $order->deliveryBasePrice;
		return $return;
	}
}