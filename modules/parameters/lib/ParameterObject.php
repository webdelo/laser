<?php
namespace modules\parameters\lib;
class ParameterObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\parameters\lib\ParameterConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}