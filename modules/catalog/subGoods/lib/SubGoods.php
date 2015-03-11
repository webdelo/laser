<?php
namespace modules\catalog\subGoods\lib;
class SubGoods extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new SubGoodsObject();
		parent::__construct($object);
	}

	public function getSubGoodsByParentId($goodId)
	{
		$filters = new \core\FilterGenerator();
		$filters->setSubquery('AND `goodId` = ?d', $goodId);
		return parent::setFilters($filters);
	}
}