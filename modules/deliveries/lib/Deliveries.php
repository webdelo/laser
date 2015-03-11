<?php
namespace modules\deliveries\lib;
class Deliveries extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new DeliveriesObject();
		$object = new \core\modules\statuses\StatusesDecorator($object);
		$object = new \core\modules\categories\CategoriesDecorator($object);
		parent::__construct($object);
	}
}