<?php
namespace modules\orders\orderComplectsGoods\lib;
class OrderComplectsGoodsDecorator extends \core\modules\base\ModuleDecorator
{
	private $goods;

	function __construct($object)
	{
		parent::__construct($object);
	}

//	public function getGoods()
//	{
//	    if(empty($this->goods)){
//			$this->goods = new OrderProcessingComplectsGoods($this->promoCodeDiscount);
//			$this->goods->setSubquery(' AND `orderId`=?d ', $this->id)->setOrderBy('`price` DESC');
//	    }
//
//	    return $this->goods;
//	}
}
