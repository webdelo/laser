<?php
namespace modules\components\lib;
class ComponentObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\components\lib\ComponentConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}