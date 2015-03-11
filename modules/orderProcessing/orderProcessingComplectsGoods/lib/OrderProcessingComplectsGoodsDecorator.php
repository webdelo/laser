<?php
namespace modules\orderProcessing\orderProcessingComplectsGoods\lib;
class OrderProcessingComplectsGoodsDecorator extends \core\modules\base\ModuleDecorator
{
	private $goods;

	function __construct($object)
	{
		parent::__construct($object);
	}

//	public function getComplectGoods()
//	{
//		if(empty($this->goods)){
//			$this->goods = new OrderProcessingComplectsGoods($this->promoCodeDiscount);
//			$this->goods->setSubquery(' AND `parentId`=?d ', $this->id);
//		}
//		return $this->goods;
//	}
}
