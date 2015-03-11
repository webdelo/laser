<?php
namespace modules\catalog\complects\complectGoods\lib;
class ComplectGoodsObject extends \core\modules\base\ModuleObjects
{
	protected $configClass = '\modules\catalog\complects\complectGoods\lib\ComplectGoodConfig';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
