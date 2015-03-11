<?php
namespace modules\orderProcessing\lib;
class OrderProcessingItems extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new OrderProcessingItemsObject();
		$object = new \core\modules\statuses\StatusesDecorator($object);
		parent::__construct($object);
	}
}