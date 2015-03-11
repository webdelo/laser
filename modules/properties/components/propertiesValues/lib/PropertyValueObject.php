<?php
namespace modules\properties\components\propertiesValues\lib;
class PropertyValueObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\properties\components\propertiesValues\lib\PropertyValueConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}