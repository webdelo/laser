<?php
namespace modules\properties\components\propertiesValues\lib;
class PropertyValues extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new PropertyValuesObject();
		parent::__construct($object);
	}
}