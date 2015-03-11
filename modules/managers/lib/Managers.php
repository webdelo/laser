<?php
namespace modules\managers\lib;
class Managers extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new ManagersObject;
		$object = new \core\modules\statuses\StatusesDecorator($object);
		parent::__construct($object);
	}
}