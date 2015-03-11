<?php
namespace modules\orders\orderComplectsGoods\lib;
class OrderComplectsGoodObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\orders\orderComplectsGoods\lib\OrderComplectsGoodConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}