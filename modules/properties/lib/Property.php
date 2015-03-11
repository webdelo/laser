<?php
namespace modules\properties\lib;
class Property extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new PropertyObject($objectId);
		$object = new \core\modules\statuses\StatusDecorator($object);
		$object = new \core\modules\categories\AdditionalCategoriesDecorator($object);
		parent::__construct($object);
	}

	/* Start: Main Data Methods */
	public function getName()
	{
		return $this->name;
	}
	public function getImagePath()
	{
		return $this->imagePath;
	}
	/*   End: Main Data Methods */

	public function getPropertyValues()
	{
		$propertyValues = new \modules\properties\components\propertiesValues\lib\PropertyValues();
		$propertyValues->setSubquery(' AND `propertyId`=?d ', (int)$this->id)->setOrderBy('`priority` ASC');
		return $propertyValues;
	}

	public function edit ($data, $fields = array()) {
		return ($this->additionalCategories->edit($data->additionalCategories)) ? $this->getParentObject()->edit($data, $fields) : false;
	}

	public function remove () {
		return $this->delete();
	}
	
	public function delete () {
		return ( $this->deleteRelations() ) ? ( $this->getPropertyValues()->delete() ) ? $this->additionalCategories->edit(array()) ? $this->getParentObject()->delete() : false : false : false ;
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