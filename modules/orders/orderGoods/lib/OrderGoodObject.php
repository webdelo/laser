<?php
namespace modules\orders\orderGoods\lib;
class OrderGoodObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\orders\orderGoods\lib\OrderGoodConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}