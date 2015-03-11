<?php
namespace modules\parameters\components\parametersValues\lib;
class ParameterValueObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\parameters\components\parametersValues\lib\ParameterValueConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}