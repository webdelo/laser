<?php
namespace modules\parameters\components\parametersValues\lib;
class ParameterValues extends \core\modules\base\ModuleObjects
{
	protected $configClass     = '\modules\parameters\components\parametersValues\lib\ParameterValueConfig';
	protected $objectClassName = '\modules\parameters\components\parametersValues\lib\ParameterValue';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
