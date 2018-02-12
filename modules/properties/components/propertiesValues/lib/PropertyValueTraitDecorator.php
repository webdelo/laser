<?php
namespace modules\properties\components\propertiesValues\lib;
trait PropertyValueTraitDecorator
{
	private $propertyValue;

	public function getPropertyValue()
	{
		return $this->propertyValueId ? new PropertyValue($this->propertyValueId) : $this->getNoop ();
	}
}