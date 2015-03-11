<?php
namespace modules\catalog\subGoods\lib;
class SubGoodsDecorator extends \core\modules\base\ModuleDecorator
{
	public $subGoods;

	function __construct($object)
	{
		parent::__construct($object);
	}

	function getSubGoods()
	{
		if(empty($this->subGoods))
			$this->subGoods = $this->getObject('\modules\catalog\subGoods\lib\SubGoods')->getSubGoodsByParentId($this->id);
		return $this->subGoods;
	}
}