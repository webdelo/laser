<?php
namespace modules\catalog\complects\complectGoods\lib;
class ComplectGoods extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new ComplectGoodsObject();
		parent::__construct($object);
	}

	public function getGoodsByComplectId($complectId)
	{
		$complectGoods = new \modules\catalog\complects\complectGoods\lib\ComplectGoods();
		$complectGoods->setSubquery('AND `complectId` = ?d', $complectId)
				   ->setOrderBy('`id` ASC');
		return $complectGoods;
	}
	
	/* Start: IOrderGoods Methods */
	public function getTotalGoodsQuantity()
	{
	    $quantity = 0;
	    foreach($this as $good)
		    $quantity = $quantity + $good->quantity;
	    return $quantity;
	}

	public function getTotalSum()
	{
		$sum = 0;
		foreach($this as $good)
			$sum = $sum + $good->getPrice() * $good->quantity;
		return $sum;
	}
	
	public function getTotalBaseSum()
	{
		$sum = 0;
		foreach($this as $good)
			$sum = $sum + $good->getCatalogPrice() * $good->quantity;
		return $sum;
	}
	/* Start: IOrderGoods Methods */

}