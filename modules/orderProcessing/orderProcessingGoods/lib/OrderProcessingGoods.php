<?php
namespace modules\orderProcessing\orderProcessingGoods\lib;
class OrderProcessingGoods extends \core\modules\base\ModuleDecorator
{
	private $totalPrice    = 0;
	private $totalQuantity = 0;
	private $promoCodeDiscount = 0;
	
	function __construct($promoCodeDiscount = 0)
	{
		$this->promoCodeDiscount = $promoCodeDiscount;
		$object = new OrderProcessingGoodsObject();
		$object = new \core\modules\statuses\StatusesDecorator($object);
		parent::__construct($object);
	}
	
	public function getTotalGoodsPrice()
	{
		if (empty($this->totalPrice))
			foreach($this as $good)
				$this->totalPrice +=$good->getTotalPrice();
		
		return $this->totalPrice;
	}
	
	public function getTotalPrice()
	{
		return $this->getTotalGoodsPrice() - $this->getPromoCodeDiscountSum();
	}

	public function getTotalQuantity()
	{
		if (empty($this->totalQuantity))
			foreach($this as $good)
				$this->totalQuantity +=$good->getQuantity();
		
		return $this->totalQuantity;
	}
	
	public function getPromoCodeDiscountSum()
	{
		return round($this->getTotalGoodsPrice() / 100 * ($this->getPromoCodeDiscount()));
	}

	public function getPromoCodeDiscount()
	{
		return $this->promoCodeDiscount;
	}
}