<?php
namespace modules\orderProcessing\orderProcessingComplectsGoods\lib;
class OrderProcessingComplectsGoods extends \core\modules\base\ModuleDecorator
{
	private $totalPrice    = 0;
	private $totalQuantity = 0;
	private $promoCodeDiscount = 0;

	function __construct($promoCodeDiscount = 0)
	{
		$this->promoCodeDiscount = $promoCodeDiscount;
		$object = new OrderProcessingComplectsGoodsObject();
		$object = new \core\modules\statuses\StatusesDecorator($object);
		parent::__construct($object);
	}
}