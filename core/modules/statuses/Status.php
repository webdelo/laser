<?php
namespace core\modules\statuses;
class Status extends \core\modules\base\ModuleDecorator
{	
	function __construct($objectId, $configObject)
	{
		$object = new StatusObject($objectId, $configObject);
		parent::__construct($object);
	}
}