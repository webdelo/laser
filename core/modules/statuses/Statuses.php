<?php
namespace core\modules\statuses;
class Statuses extends \core\modules\base\ModuleDecorator
{
	function __construct($configObject)
	{
		$object = new StatusesObject($configObject);
		parent::__construct($object);
	}
}