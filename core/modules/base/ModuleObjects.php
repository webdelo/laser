<?php
namespace core\modules\base;
abstract class ModuleObjects extends ModuleAbstract implements \Iterator, \Countable, \ArrayAccess, \Serializable
{
	protected $quantityItemsOnSubpageList = array(10,25,50,100);
	protected $objectsList    = array();
	protected $objectsIdArray = array();
	protected $pager;
	protected $filters;

	protected $_loadWithoutRemovedObjects = true;

	function __construct($configObject)
	{
		parent::__construct($configObject);
		$this->filters = new \core\FilterGenerator;
	}

	public function isModuleObjects()
	{
		return true;
	}

	public function setObjectsId($objectsIdArray)
	{
		$this->objectsIdArray = $this->adaptObjectsId($objectsIdArray);
		return $this;
	}

	private function adaptObjectsId($objectsIdValue)
	{
		if (is_array($objectsIdValue))
			return $objectsIdValue;

		$objectsIdValue = (int)$objectsIdValue;
		if ($objectsIdValue)
			return array($objectsIdValue);
		return array();
	}

	public function setFilters($filterGenerator)
	{
		$this->checkFilterObject($filterGenerator)->resetObjects();
		$this->filters = $filterGenerator;
		return $this;
	}

	private function checkFilterObject($filterGenerator)
	{
		if ($filterGenerator instanceof \core\FilterGenerator)
			return $this;
		throw new \Exception('Passed not FilterGenerator object to ModuleObjects::setFilters in class '.get_class($this).'!');
	}

	public function resetFilters()
	{
		$this->resetObjects();
		$this->reset()->resetErrors();
		$this->filters = new \core\FilterGenerator;
		return $this;
	}

	public function setSubquery($subquery, $data = null)
	{
		call_user_func_array(array($this->filters, 'setSubquery'), func_get_args());
		return $this;
	}

	public function setOrderBy($subquery)
	{
		$this->filters->setOrderBy($subquery);
		return $this;
	}

	public function setLimit($subquery)
	{
		$this->filters->setLimit($subquery);
		return $this;
	}

	public function setQuantityItemsOnSubpageList($quantityItemsOnSubpageArray)
	{
		if (is_array($quantityItemsOnSubpageArray)) {
			$this->quantityItemsOnSubpageList = $quantityItemsOnSubpageArray;
			return $this;
		}
		throw new \Exception('The value in argument '.get_class($this).'::setQuantityItemsOnSubpageList() must be an array!');
	}

	public function setPager($quantity)
	{
		$quantity = $this->getValidQuantityForSubpage($quantity);
		$this->pager = new \core\pager\Pager();
		$this->pager->setData($quantity,$this->count());
		$this->resetObjects()->setLimit($this->pager->getLimit());
		return $this;
	}

	public function getValidQuantityForSubpage($quantity)
	{
		if (array_search($quantity, $this->quantityItemsOnSubpageList))
			return $quantity;
		return (int)$this->quantityItemsOnSubpageList[0];
	}

	public function getQuantityItemsOnSubpageListArray()
	{
		return $this->quantityItemsOnSubpageList;
	}

	public function getPager()
	{
		if (empty($this->pager))
			return false;
		return $this->pager;
	}

	private function resetObjects()
	{
		$this->objectsIdArray = $this->objectsList = array();
		return $this;
	}

	public function getObjects()
	{
		return (empty($this->objectsList)) ? $this->loadObjects() : $this->objectsList;
	}

	public function loadObjects()
	{
		$idArray = new \core\ArrayWrapper($this->getIdArray());
		foreach ($idArray as $objId) {
			$id = $objId[$this->idField];
			$this->objectsList[$id] = $this->getModuleObject($id);
		}
		return $this->objectsList;
	}

	public function getIdArray()
	{
		return (empty($this->objectsIdArray)) ? $this->loadIdArray() : $this->objectsIdArray;
	}

	private function loadIdArray()
	{
		return $this->objectsIdArray = $this->reset()->getAll($this->idField, $this->getFilters());
	}

	private function getFilters ()
	{
		$this->setRemovedObjectsFilters();
		return $this->filters->getFiltersArray();
	}

	private function setRemovedObjectsFilters()
	{
		if ($this->_loadWithoutRemovedObjects) {
			$removedStatus = $this->getRemovedStatus();
			if ($removedStatus)
				$this->filters->setSubquery('AND `statusId` != ?d', $removedStatus);
		}
		return $this;
	}

	public function loadWithRemovedObjects()
	{
		$this->_loadWithoutRemovedObjects = false;
		return $this;
	}

	public function loadWithoutRemovedObjects()
	{
		$this->_loadWithoutRemovedObjects = true;
		return $this;
	}

	public function getTree()
	{
		$list = $this->reset()->getAll($this->idField.',parentId', $this->filters);
		if (!empty($list)) {
			$objectConfig = $this->_moduleConfig->removePostfix();
			$objectClass = $this->_moduleConfig->getObjectClass();
			return new \core\modules\utils\tree\TreeGenerator($list, $objectClass, $objectConfig);
		}
	}

	public function countObjects($filters = null)
	{
		return $this->countAll(($filters) ? $filters : $this->filters->getFiltersArray());
	}

	private function checkObjectsIdArray()
	{
		if (!$this->objectsIdArray || !is_array($this->objectsIdArray))
			throw new \Exception('$objectsIdArray is not initialized in '.get_class($this).' class.');
	}

	public function delete()
	{
		foreach ($this->getIdArray() as $objectId) {
			$id = $objectId[$this->idField];
			$object = $this->getObjectById($id);
			$object->delete();
		}
		return true;
	}

	public function edit($data, $fields = array())
	{
		foreach ($this->getIdArray() as $objectId) {
			$id = $objectId[$this->idField];
			$object = $this->getObjectById($id);
			$object->edit($data);
		}
		return true;
	}

	public function add($data, $fields = null)
	{
		$fields = is_array($fields) ? $fields : $this->_moduleConfig->getObjectFields();
		return $this->baseAdd($data, $fields);
	}

	public function isExist($id)
	{
		return $this->isFieldExist($id);
	}

	public function getIdByAlias($alias)
	{
		return $this->getField($this->idField,$alias,'alias');
	}

	public function getIdByName($name)
	{
		return $this->getField($this->idField,$name,'name');
	}

	public function getIdByCode($code)
	{
		return $this->getField($this->idField,$code,'code');
	}

	public function getAliasById($id)
	{
		return $this->getField('alias',$id);
	}

	public function getObjectByAlias($alias)
	{
		$id = $this->getIdByAlias($alias);
		return ($id) ? $this->getModuleObject($id) : false;
	}

	public function getObjectByCode($code)
	{
		$id = $this->getIdByCode($code);
		return ($id) ? $this->getModuleObject($id) : false;
	}

	protected function &getModuleObject($id)
	{
		$objectConfig = clone $this->_moduleConfig;
		$objectConfig->removePostfix();
		return \core\ObjectPool::getInstance()->getObject($this->getConfig()->getObjectClass(), $id, $objectConfig);
	}

	public function getObjectById($id)
	{
		$id = (int)$id;
		return ($id) ? $this->getModuleObject($id) : false;
	}

	public function objectExists($objectId)
	{
		return array_key_exists($objectId, $this->getObjects());
	}

	public function getArray() {
		$this->getObjects();
		return $this->objectsIdArray;
	}

	public function getIdList() {
		$this->getObjects();
		$idList = array();
		foreach ($this->objectsIdArray as $object) {
			$idList[] = $object[$this->idField];
		}
		return $idList;
	}

	/* Start: Iterator Methods */
	function rewind() {
		reset($this->objectsList);
	}

	function current() {
		$this->getObjects();
		return current($this->objectsList);
	}

	function key() {
		return key($this->objectsList);
	}

	function next() {
		next($this->objectsList);
	}

	function valid() {
		$this->getObjects();
		return !!(current($this->objectsList));
	}
	/* End: Iterator Methods */

	/* Start: Countable Methods */
	public function count()
	{
		$this->getObjects();
		return count($this->objectsList);
	}
	/* End: Countable Methods */

	public function __destruct()
	{
		//$this->unsetObjects();
	}

	public function unsetObjects()
	{
		array_walk($this->objectsList, array($this, 'unsetInstantObjects'));
		$this->objectsList = $this->objectsIdArray = null;
		return $this;
	}

	private function unsetInstantObjects($object, $key)
	{
		return $object->unsetObject();
	}

	/* Start: ArrayAccess Methods */
	public function offsetExists($offset)
	{
		$this->getObjects();
		return isset($this->objectsList[$offset]);
	}

	public function offsetGet($offset)
	{
		$this->getObjects();
		return $this->offsetExists($offset) ? $this->objectsList[$offset] : null;
	}

	public function offsetSet($offset, $value)
	{
		$this->getObjects();
		if (is_null($offset)) {
			$this->objectsList[] = $value;
		} else {
			$this->objectsList[$offset] = $value;
		}
	}

	public function offsetUnset($offset)
	{
		$this->getObjects();
		unset($this->objectsList[$offset]);
	}
	/* End: ArrayAccess Methods */

	/* Start: Serializable interface Methods */
	public function serialize()
    {
		$data['_moduleConfig'] = $this->_moduleConfig;
		$data['pager'] = $this->pager;
		$data['filters'] = $this->filters;
		$data['quantityItemsOnSubpageList'] = $this->quantityItemsOnSubpageList;
		$data['_loadWithoutRemovedObjects'] = $this->_loadWithoutRemovedObjects;
		return serialize($data);
    }

	public function unserialize($data)
    {
		$data = unserialize($data);
		$this->pager = $data['pager'];
		$this->filters = $data['filters'];
		$this->quantityItemsOnSubpageList = $data['quantityItemsOnSubpageList'];
		$this->_loadWithoutRemovedObjects = $data['_loadWithoutRemovedObjects'];
		parent::__construct($data['_moduleConfig']);
	}
	/* End: Serializable interface Methods */
}