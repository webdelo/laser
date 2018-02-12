<?php
namespace modules\parameters\lib;
class Parameter extends \core\modules\base\ModuleObject
{
	use \core\traits\ObjectPool,
		\core\modules\statuses\StatusTraitDecorator,
		\core\traits\RequestHandler,
		\core\i18n\TextLangParserTraitDecorator,
		\core\modules\categories\AdditionalCategoriesTraitDecorator;

	protected $configClass = '\modules\parameters\lib\ParameterConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

	public function getChooseMethod()
	{
		if ( !$this->chooseMethod ) {
			$this->chooseMethod = $this->chooseMethodId
				? new \modules\parameters\components\chooseMethods\lib\ChooseMethod($this->chooseMethodId)
				: $this->getNoop();
		}
		return $this->chooseMethod;
	}

	/* Start: Main Data Methods */
	public function getName($lang = null)
	{
		return $this->getTextFromLangParser($this->loadObjectInfo()->objectInfo['name'], $lang);
	}
	
	public function getImagePath()
	{
		return $this->imagePath;
	}
	/*   End: Main Data Methods */

	public function getParameterValues()
	{
		$parameterValues = new \modules\parameters\components\parametersValues\lib\ParameterValues();
		$parameterValues->setSubquery(' AND `parameterId`=?d ', (int)$this->id)
						->setOrderBy('`priority` ASC');
		return $parameterValues;
	}

	public function getActualParameterValues($ownerObject)
	{
		$parameterValues = new \modules\parameters\components\parametersValues\lib\ParameterValues();
		$existsQuery = ' SELECT `objectId` FROM `'.$ownerObject->mainTable().'_parameters_values_relation` ';
		$parameterValues->setSubquery(' AND `parameterId`=?d AND id IN ('.$existsQuery.')', (int)$this->id)
						->setOrderBy('`priority` ASC');
		return $parameterValues;
	}

	public function edit ($data = null, $fields = array(), $rules = array()) {
		return ($this->getAdditionalCategories()->edit($data->additionalCategories))
			? $this->_edit($data, $fields, $rules)
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
			? ($this->getParameterValues()->delete()
				? ($this->getAdditionalCategories()->edit(array())
					? $this->delete()
					: false)
				: false)
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

	public function isCheckbox()
	{
		return (int)$this->chooseMethodId === 2;
	}
}