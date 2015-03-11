<?php
namespace modules\properties\lib;
class PropertyObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\properties\lib\PropertyConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}