<?php
namespace modules\parameters\components\parametersValues\lib;
class ParameterValues extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new ParameterValuesObject();
		parent::__construct($object);
	}
}