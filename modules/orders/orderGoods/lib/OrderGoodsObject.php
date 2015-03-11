<?php
namespace modules\orders\orderGoods\lib;
class OrderGoodsObject extends \core\modules\base\ModuleObjects
{
	protected $configClass = '\modules\orders\orderGoods\lib\OrderGoodConfig';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
