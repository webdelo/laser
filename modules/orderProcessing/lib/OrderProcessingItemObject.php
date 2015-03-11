<?php
namespace modules\orderProcessing\lib;
class OrderProcessingItemObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\orderProcessing\lib\OrderProcessingItemConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}