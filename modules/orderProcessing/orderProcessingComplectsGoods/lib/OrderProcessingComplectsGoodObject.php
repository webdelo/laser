<?php
namespace modules\orderProcessing\orderProcessingComplectsGoods\lib;
class OrderProcessingComplectsGoodObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\orderProcessing\orderProcessingComplectsGoods\lib\OrderProcessingComplectsGoodConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}