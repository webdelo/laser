<?php
namespace modules\properties\components\propertiesValues\lib;
class RelationsObject extends \core\modules\base\ModuleRelations
{
	protected $configClass = '\modules\properties\components\propertiesValues\lib\RelationsConfig';

	function __construct($ownerId, $configObject)
	{
		parent::__construct($ownerId, new $this->configClass($configObject));
	}
	
	public function edit($post, $fields = array())
	{
		$relation = $this->getObjectById($post->id);
		
		return $relation ? $relation->edit($post, $fields) : $this->add($post) ;
	}
	
	public function add($data, $fields = null)
	{
		if ($fields)
			throw new \Exception('Field selection is not possible in '.__METHOD__.'!');
		
		return $this->isMultipleAdding($data) ? $this->addArray($data) : $this->addOne($data);
	}
	
	private function isMultipleAdding ($data) {
		return !isset($data['propertyValueId']);
	}
	
	private function addArray($data) {
		foreach ($data as $propertyId=>$row) {
			if (!empty($value)) {
				$rowData = array(
					'ownerId'          => $this->ownerId,
					'propertyValueId'  => (int)$row['propertyValueId'],
					'value'            => $row['value'],
					'measureId'        => (int)$row['measureId']
				);
				$this->reset();
				\core\modules\base\ModuleObjects::add($rowData, $this->getConfig()->getObjectFields());
			}
		}
		return true;
	}
	
	private function addOne($data) {
		
		return \core\modules\base\ModuleObjects::add($data, $this->getConfig()->getObjectFields());
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
}