<?php
namespace modules\properties\components\propertiesValues\lib;
class PropertyValueDecorator extends \core\modules\base\ModuleDecorator
{
	private $propertyValue;
	
	function __construct($object)
	{
		parent::__construct($object);
	}
	
	function getPropertyValue() 
	{
		return $this->propertyValueId ? new PropertyValue($this->propertyValueId) : $this->getNoop ();
	}
}