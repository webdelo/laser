<?php
namespace core\modules\base;
abstract class ModuleObject extends ModuleAbstract implements \Serializable
{
	protected $objectId;
	protected $objectInfo = array();

	function __construct($objectId, $configObject)
	{
		parent::__construct($configObject);
		$this->setObjectId($objectId);
	}

	private function setObjectId($objectId)
	{
		$this->objectId = $objectId;
		$this->checkObjectId();
		return $this;
	}

	private function checkObjectId()
	{
		if (empty($this->objectId))
			throw new \Exception('Passed invalid ID = "'.$this->objectId.'" for object initialization in class '.get_class($this).'!');
	}

	public function isModuleObject()
	{
		return true;
	}

	public function __toString()
	{
		return get_class($this).':'.$this->objectId.':'.$this->mainTable;
	}

	function __set($key, $value)
	{
		$this->setValue($key, $value);
	}

	public function setValue($key, $value)
	{
		$this->isInitialized()->loadObjectInfo();
		$key = (string)$key;
		if (in_array($key, array($this->idField,'objectId')))
			throw new \Exception('You can\'t change ID value in initialized object!');

		$setterName = 'set'.ucfirst($key).'Field';
		if (method_exists($this, $setterName)) {
			throw new \Exception('Must use setter-method "'.$setterName.'" in class '.get_class($this).'!');
		} elseif (property_exists($this, $key)) {
			$this->$key = $value;
		} else {
			$this->objectInfo[$key] = $value;
		}
		return $this;
	}

	public function isInitialized()
	{
		if (!$this->objectId) {
			throw new \Exception('Object '.get_class($this).' is not Initialized!');
		}
		return $this;
	}

	function __get($key)
	{
		if (in_array($key, array('id', $this->getIdField())))
			return $this->objectId;

		$this->isInitialized()->loadObjectInfo();
		if (isset($this->objectInfo[$key])){
//			if (method_exists($this, $getterName = 'get'.ucfirst($key)))
//				return $this->$getterName();
				//throw new \Exception('Must use getter-method "'.$getterName.'" in class '.get_class($this).'!');
			return $this->objectInfo[$key];
		}
	}

	protected function loadObjectInfo()
	{
		$this->objectInfo = $this->getObjectInfo();
		return $this;
	}

	public function getObjectInfo()
	{
		if (empty($this->objectInfo)) {
			$this->objectInfo = $this->changeOutputRules($this->getConfig()->outputRules())->getInfoById($this->objectId);
			if (!$this->objectInfo)
				throw new \Exception('Object with id='.$this->objectId.' was not found in class '.get_class($this).' (DB_Table::'.$this->mainTable().')!');
		}
		return $this->objectInfo;
	}

	public function __isset($name)
	{
		return isset($this->objectInfo[$name]);
	}

	public function delete()
	{
		return ($this->getRemovedStatus()) ? $this->removeByStatus() : $this->deleteById($this->objectId) ;
	}

	protected function removeByStatus()
	{
		return $this->edit(array('statusId' => $this->getRemovedStatus()));
	}

	// Method alias for delete()
	public function remove()
	{
		return $this->delete();
	}

	public function edit( $data = null, $fields = array(), $rules = array() )
	{
		$dataTemp = isset($data) ? $data : $this->getObjectInfo();

		if ( isset($data) ){
			if ( empty($fields) )
				$fields = $this->getKeyUpdatedFields($data);
		} else
			$fields = $this->_moduleConfig->getObjectFields();

		$data = $dataTemp;

		$data[$this->idField] = $this->objectId;
		$fields[] = $this->idField;

		return $this->baseEdit($data, $fields, $rules) ? $this->updateNewFieldsInObjectInfo($data) : false;
	}

	public function editField($value, $field, $rules = array()) {
		$values = array($field=>$value);
		$fields = array($field);
		$data = array_merge($this->getObjectInfo(), $values);

		$data[$this->idField] = $this->objectId;
		$fields[] = $this->idField;
		return $this->baseEdit($data, $fields, $rules) ? $this->updateNewFieldsInObjectInfo($data) : false;
	}

	protected function getActualFields($data, $fields, $objectFields = null)
	{
		$objectFields = isset($objectFields) ? $objectFields : $this->_moduleConfig->getObjectFields();
		if ( isset($data) ){
			if (empty($fields) )
				$fields = $this->getKeyUpdatedFields($data, $objectFields);
		} else
			$fields = $objectFields;
		return $fields;
	}

	protected function getKeyUpdatedFields($data, $objectFields = null)
	{
		return array_keys($this->getSharedElements($data, $objectFields));
	}

	private function getSharedElements($data, $objectFields = null)
	{
		$objectFields = isset($objectFields) ? $objectFields : $this->_moduleConfig->getObjectFields();
		$data = ($data instanceof \core\ArrayWrapper) ? $data->getArray() : $data;
		return array_intersect_key($data, array_flip($objectFields));
	}

	protected function updateNewFieldsInObjectInfo($data)
	{
		$this->objectInfo = array_merge($this->getObjectInfo(), $this->getSharedElements($data));
		return (int)$this->objectInfo[$this->idField];
	}

	/* Start: Serializable interface Methods */
	public function serialize()
    {
		$data['objectId'] = $this->objectId;
		$data['_moduleConfig'] = $this->_moduleConfig;
		return serialize($data);
    }

	public function unserialize($data)
    {
		$data = unserialize($data);
		$this->objectId = $data['objectId'];
		parent::__construct($data['_moduleConfig']);
	}
	/* End: Serializable interface Methods */
}