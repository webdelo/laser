<?php
namespace modules\properties\components\propertiesValues\lib;
class PropertyValue extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new PropertyValueObject($objectId);
		$object = new \modules\measures\lib\MeasuresDecorator($object);
		parent::__construct($object);
	}
	
	public function getProperty()
	{
		if(empty($this->property))
			$this->property = $this->getObject('\modules\properties\lib\Property', $this->propertyId);
		return $this->property;
	}

	/* Start: Main Data Methods */
	public function getName()
	{
		return $this->getProperty()->name;
	}
	public function getImagePath()
	{
		return $this->getProperty()->imagePath;
	}
	public function getValue()
	{
		return $this->value;
	}
	/*   End: Main Data Methods */

	public function remove () {
		return $this->delete();
	}
	
	public function delete () {
		return ( $this->deleteRelations() ) ? $this->getParentObject()->delete() : false ;
	}
	
	private function deleteRelations ()
	{
		foreach ($this->getConfig()->relations() as $table=>$field) {
			$query = ' DELETE FROM `'.$table.'` WHERE `'.$field['idField'].'`=?d ';
			if ( !\core\db\Db::getMysql()->query( $query, array($this->id) ) )  {
				return false;
			}
		}
		return true;
	}
}