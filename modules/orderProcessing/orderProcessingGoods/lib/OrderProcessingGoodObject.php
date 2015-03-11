<?php
namespace modules\orderProcessing\orderProcessingGoods\lib;
class OrderProcessingGoodObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\orderProcessing\orderProcessingGoods\lib\OrderProcessingGoodConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}