<?php
namespace modules\catalog\colors\lib;
class ColorsGroupObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\catalog\colors\lib\ColorsGroupConfig';
	
	function __construct($objectId, $configObject)
	{
	    parent::__construct($objectId, new $this->configClass($configObject));
	}
}