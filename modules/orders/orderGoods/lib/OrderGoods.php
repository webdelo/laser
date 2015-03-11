<?php
namespace modules\orders\orderGoods\lib;
class OrderGoods extends \core\modules\base\ModuleDecorator implements \interfaces\IOrderGoods
{
	private $promoCodeDiscount = 0;

	function __construct($promoCodeDiscount = 0)
	{
		$this->promoCodeDiscount = $promoCodeDiscount;
		$object = new OrderGoodsObject();
		parent::__construct($object);
	}

	public function getGoodsByOrderId($orderId)
	{
		$this->setSubquery('AND `orderId` = ?d', $orderId)
			 ->setOrderBy('`id` ASC');
		return $this;
	}

	/* Start: IOrderGoods Methods */
	public function getTotalGoodsQuantity()
	{
		$quantity = 0;
		foreach($this as $good)
			$quantity += $good->quantity;
		return $quantity;
	}

	public function getTotalGoodsSum()
	{
		$sum = 0;
		foreach($this as $good)
			$sum += $good->getPrice();
		return round($sum);
	}
	
	public function getTotalGoodsBaseSum()
	{
		$sum = 0;
		foreach($this as $good)
			$sum += $good->getBasePrice();
		return round($sum);
	}

	public function getTotalSum()
	{
		return round($this->getTotalGoodsSum() - $this->getPromoCodeDiscountSum());
	}
	
	public function getIncome()
	{
		return round($this->getTotalSum() - $this->getTotalGoodsBaseSum());
	}

	public function getTotalGoodsDiscount()
	{
		$sum = 0;
		foreach($this as $good)
			$sum += $good->promoCodeDiscount;
		return $sum;
	}

	public function getPromoCodeDiscountSum()
	{
		return round($this->getTotalGoodsSum() / 100 * ($this->getPromoCodeDiscount()));
	}

	public function getPromoCodeDiscount()
	{
		return $this->promoCodeDiscount;
	}

	public function getTotalDiscount()
	{
		return $this->getTotalGoodsDiscount() + $this->getPromoCodeDiscountSum();
	}
	/* Start: IOrderGoods Methods */

	public function isZeroPriceGoods()
	{
		foreach ($this as $good)
			if($good->getGood()->isZeroPrice())
				return true;
		return false;
	}

	public function isNotZeroPriceGoods()
	{
		return ! $this->isZeroPriceGoods();
	}

}