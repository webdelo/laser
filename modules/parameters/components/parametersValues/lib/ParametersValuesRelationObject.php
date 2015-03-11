<?php
namespace modules\parameters\components\parametersValues\lib;
class ParametersValuesRelationObject extends \core\modules\base\ModuleRelations
{
	protected $configClass = '\modules\parameters\components\parametersValues\lib\ParametersValuesRelationConfig';

	function __construct($ownerId, $configObject)
	{
		parent::__construct($ownerId, new $this->configClass($configObject));
	}
	
	public function add($data, $fields = null)
	{
		if ($fields)
			throw new \Exception('Field selection is not possible in ParametersValuesRelation::add() in '.get_class().'!');
		if (is_object($data)) {
			foreach ($data as $parameterId=>$value) {
				if (!empty($value)) {
					$rowData = array(
						'ownerId'  => $this->ownerId,
						'objectId' => (int)$value,
						'parameterId' => (int)$parameterId
					);
					$this->reset();
					\core\modules\base\ModuleObjects::add($rowData, $this->getConfig()->getObjectFields());
				}
			}
			return true;
		}
		return false;
	}
	
	public function delete()
	{
		$query = '
			DELETE FROM
				`'.$this->mainTable().'`
			WHERE
				`ownerId` = ?d
			';
		return \core\db\Db::getMysql()->query($query, array($this->ownerId));
	}

	public function edit($data)
	{
		return ($this->delete())    ?    ( isset($data) ? $this->add($data) : true )    :    false;
	}
}