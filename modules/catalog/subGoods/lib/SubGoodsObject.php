<?php
namespace modules\catalog\SubGoods\lib;
class SubGoodsObject extends \core\modules\base\ModuleObjects
{
	protected $configClass     = '\modules\catalog\subGoods\lib\SubGoodConfig';
	protected $objectClassName = '\modules\catalog\subGoods\lib\SubGood';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
