<?php
namespace modules\properties\components\propertiesValues\lib;
class PropertyValuesObject extends \core\modules\base\ModuleObjects
{
	protected $configClass     = '\modules\properties\components\propertiesValues\lib\PropertyValueConfig';
	protected $objectClassName = '\modules\properties\components\propertiesValues\lib\PropertyValue';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
