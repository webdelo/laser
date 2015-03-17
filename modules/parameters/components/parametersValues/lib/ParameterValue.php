<?php
namespace modules\parameters\components\parametersValues\lib;
class ParameterValue extends \core\modules\base\ModuleObject
{
	use \core\traits\RequestHandler,
		\core\i18n\TextLangParserTraitDecorator;
	
	protected $configClass = '\modules\parameters\components\parametersValues\lib\ParameterValueConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

	public function getParameter()
	{
		if(empty($this->parameter))
			$this->parameter = $this->getObject('\modules\parameters\lib\Parameter', $this->parameterId);
		return $this->parameter;
	}

	/* Start: Main Data Methods */
	public function getName()
	{
		return $this->getParameter()->getName();
	}
	
	public function getImagePath()
	{
		return $this->getParameter()->imagePath;
	}
	
	public function getValue( $lang = null )
	{
//				var_dump($this->loadObjectInfo()->objectInfo['value']);
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