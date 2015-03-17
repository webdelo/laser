<?php
namespace modules\properties\components\propertiesValues\lib;
class RelationsDecorator extends \core\modules\base\ModuleDecorator
{
	private $propertiesValues;
	private $propertyValue = array();
	private $property;
	
	function __construct($object)
	{
		parent::__construct($object);
	}
	
	function getProperties()
	{
		return new Relations($this->id, $this->_object);
	}
	
	function getPropertiesByPropertyAlias($alias)
	{
		$properties = new Relations($this->id, $this->_object);
		$properties->reset()->setSubquery(' AND `propertyValueId` IN ( SELECT `id` FROM `tbl_properties_values` as propertiesValues WHERE `propertyId` IN ( SELECT `id` FROM `tbl_properties` WHERE `alias` = \'?s\' )  ) ', $alias);
		$properties->setOrderBy(' `priority` ASC ');
		return $properties;
	}
	
	function getPropertyValueById($id)
	{
		if ( !$id ) {
			throw new \Exception('Don\' transmited argument in method '.__METHOD__, 1024);
		}
		
		if (isset($this->propertyValue[$id])) 
			return $this->propertyValue[$id];
		
		$properties = new Relations($this->id, $this->_object);
		$properties->setSubquery(' AND `propertyValueId` = ?d ', $id);
		
		$this->propertyValue[$id] = $properties->count()==1 ? $properties->current() : $this->getNoop();

		return $this->propertyValue[$id];

	}
	
	function getPropertyById($id)
	{
		if ( !$id ) {
			throw new \Exception('Don\' transmited argument in method '.__METHOD__, 1024);
		}
		
		if (isset($this->property[$id])) 
			return $this->property[$id];
		
		$this->property[$id] = new \modules\properties\lib\Property($this->id);

		return $this->property[$id];

	}
	
	function getPropertyByAlias($alias)
	{
		if ( !$alias ) {
			throw new \Exception('Don\' transmited argument in method '.__METHOD__, 1024);
		}
		
		if (isset($this->property[$id])) 
			return $this->property[$id];
		
		$properties = new \modules\properties\lib\Properties();
		
		$this->property[$id] = $properties->getObjectByAlias($alias);

		return $this->property[$id];

	}
}