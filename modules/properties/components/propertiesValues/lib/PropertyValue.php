<?php
namespace modules\properties\components\propertiesValues\lib;
class PropertyValue extends \core\modules\base\ModuleObject
{
	use \modules\measures\lib\MeasuresTraitDecorator,
		\core\traits\RequestHandler,
		\core\i18n\TextLangParserTraitDecorator;

	protected $configClass = '\modules\properties\components\propertiesValues\lib\PropertyValueConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
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
		return $this->getProperty()->getName();
	}
	public function getImagePath()
	{
		return $this->getProperty()->imagePath;
	}
	public function getValue( $lang = null )
	{
		return $this->getTextFromLangParser($this->loadObjectInfo()->objectInfo['value'], $lang);
	}
	/*   End: Main Data Methods */

	public function remove () {
		return $this->delete();
	}
	
	public function edit($data = null, $fields = array(), $rules = array()) 
	{
		$compacter = new \core\i18n\TextLangCompacter($this, $data);
		return parent::edit($compacter->getPost(), $fields, $rules);
	}

	public function delete () {
		return ( $this->deleteRelations() ) ? parent::delete() : false ;
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