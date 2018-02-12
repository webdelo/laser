<?php
namespace modules\properties\lib;
class Property extends \core\modules\base\ModuleObject
{
	use \core\traits\ObjectPool,
		\core\modules\statuses\StatusTraitDecorator,
		\core\traits\RequestHandler,
		\core\i18n\TextLangParserTraitDecorator,
		\core\modules\categories\AdditionalCategoriesTraitDecorator;

	protected $configClass = '\modules\properties\lib\PropertyConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

	/* Start: Main Data Methods */
	public function getName($lang = null)
	{
		return $this->getTextFromLangParser($this->loadObjectInfo()->objectInfo['name'], $lang);
	}
	
	public function getImagePath()
	{
		return $this->loadObjectInfo()->objectInfo['imagePath'];
	}
	/*   End: Main Data Methods */

	public function getPropertyValues()
	{
		$propertyValues = new \modules\properties\components\propertiesValues\lib\PropertyValues();
		$propertyValues->setSubquery(' AND `propertyId`=?d ', (int)$this->id)->setOrderBy('`priority` ASC');
		return $propertyValues;
	}

	public function edit ($data = null, $fields = array(), $rules = array()) {
		return ($this->getAdditionalCategories()->edit($data->additionalCategories)) 
			? $this->_edit($data, $fields, $rules = array()) 
			: false;
	}
	
	public function _edit($data = null, $fields = array(), $rules = array()) 
	{
		$compacter = new \core\i18n\TextLangCompacter($this, $data);
		return parent::edit($compacter->getPost(), $fields, $rules);
	}

	public function remove () {
		return $this->delete();
	}

	public function delete () {
		return ( $this->deleteRelations() )
			? ( $this->getPropertyValues()->delete() )
				? $this->getAdditionalCategories()->edit(array())
					? parent::delete()
					: false
				: false
			: false;
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