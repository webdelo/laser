<?php
namespace modules\catalog\availability\lib;
class AvailabilityObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\catalog\availability\lib\AvailabilityConfig';
	
	function __construct($objectId, $configObject)
	{
		parent::__construct($objectId, new $this->configClass($configObject));
	}
}