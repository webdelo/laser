<?php
namespace core\modules\utils\tree;
class TreeGenerator implements \Iterator
{
	private $parentIdKey = 'parentId';
	private $elementIdKey = 'id';

	private $objectClass;
	private $objectConfig;

	private $_fullList;
	private $_tree;

	public function __construct($fullList, $objectClass, $objectConfig, $parentIdKey = 'parentId', $elementIdKey = 'id')
	{
		$this->setArray($fullList)
			 ->setObjectClass($objectClass)
			 ->setObjectConfig($objectConfig)
			 ->setParentIdKey($parentIdKey)
			 ->setElementIdKey($elementIdKey)
			 ->getObjectTree();
	}

	private function setArray($fullList)
	{
		$this->_fullList = $fullList;
		return $this->checkFullList();
	}

	private function checkFullList()
	{
		if (is_array($this->_fullList) && !empty($this->_fullList))
			return $this;
		throw new \Exception('Is not valid list tree array in '.get_class($this).'!');
	}

	private function setObjectClass($objectClass)
	{
		$this->objectClass = $objectClass;
		return $this->checkObjectClass();
	}

	private function checkObjectClass()
	{
		$this->objectClass = (string)$this->objectClass;
		if (!empty($this->objectClass))
			return $this;
		throw new \Exception('Object Class Variable is empty in '.get_class($this).'!');
	}

	private function setObjectConfig($objectConfig)
	{
		$this->objectConfig = $objectConfig;
		return $this->checkObjectConfig();
	}

	private function checkObjectConfig()
	{
		if (is_object($this->objectConfig))
			return $this;
		throw new \Exception('Invalid config-objects in '.get_class($this).'!');
	}

	private function setParentIdKey($parentIdKey)
	{
		$this->parentIdKey = $parentIdKey;
		return $this;
	}

	private function setElementIdKey($elementIdKey)
	{
		$this->elementIdKey = $elementIdKey;
		return $this;
	}

	private function getHeadElements()
	{
		return array_filter($this->_fullList, array($this, '_filterHeadElements'));
	}

	private function _filterHeadElements($var)
	{
		return ((int)$var[$this->parentIdKey] == 0);
	}

	public function __call($methodName, $arguments)
	{
		$this->checkAndInstantTree();
		return call_user_func_array(array($this->_tree, $methodName), $arguments);
	}

	private function checkAndInstantTree()
	{
		if (empty($this->_tree))
			$this->_tree = new TreeListWrapper ($this->getHeadElements(), $this->_fullList, $this->objectClass, $this->objectConfig, $this->parentIdKey, $this->elementIdKey);
		return $this->_tree;
	}

	public function getObjectTree()
	{
		return $this->checkAndInstantTree();
	}

	public function __get($varName)
	{
		$this->checkAndInstantTree();
		return $this->_tree->$varName;
	}

	public function __set($varName, $varValue)
	{
		$this->checkAndInstantTree();
		$this->_tree->$varName = $varValue;
	}

	/* Start: Iterator Methods */
	function rewind() {
		$this->_tree->rewind();
	}

	function current() {
		return $this->_tree->current();
	}

	function key() {
		return $this->_tree->key();
	}

	function next() {
		$this->_tree->next();
	}

	function valid() {
		return $this->_tree->valid();
	}
	/* End: Iterator Methods */

}

class TreeListWrapper implements \Iterator
{
	private $parentIdKey = 'parentId';
	private $elementIdKey = 'id';

	private $objectClass;
	private $objectConfig;
	private $array;

	private $_currentElementId;
	private $_position;
	private $_fullList;
	public $_tree;

	public function __construct($array, &$_fullList, $objectClass, $objectConfig, $parentIdKey, $elementIdKey)
	{
		$this->setParams($array, $_fullList, $objectClass, $objectConfig, $parentIdKey, $elementIdKey);
	}

	private function setParams($array, &$_fullList, $objectClass, $objectConfig, $parentIdKey, $elementIdKey)
	{
		$this->parentIdKey  = $parentIdKey;
		$this->elementIdKey = $elementIdKey;
		$this->objectClass  = $objectClass;
		$this->objectConfig = $objectConfig;
		$this->_fullList    = &$_fullList;
		$this->array        = $array;
		return $this;
	}

	private function getObjectTree()
	{
		foreach ($this->array as $key=>$elem) {
			$nodeArray = null;
			$nodeArray['object'] = new $this->objectClass($elem[$this->elementIdKey], $this->objectConfig);
			$childsArray = $this->getChildsElements($elem[$this->elementIdKey]);
			if (!empty($childsArray))
				$nodeArray['childs'] = new TreeListWrapper($childsArray, $this->_fullList, $this->objectClass, $this->objectConfig, $this->parentIdKey, $this->elementIdKey);
			$tree[] = new TreeNodeWrapper($nodeArray);
		}
		$this->_tree = $tree;
		return $this;
	}

	private function getChildsElements($elementId)
	{
		$this->_currentElementId = (int)$elementId;
		$childListElements = array_filter($this->_fullList, array($this, '_filterChildElements'));
		return $childListElements;
	}

	private function _filterChildElements($var)
	{
		return ((int)$var[$this->parentIdKey] == (int)$this->_currentElementId);
	}

	/* Start: Iterator Methods */
	function rewind() {
		$this->_position = 0;
	}

	function current() {
		$this->checkAndInstantTree();
		return $this->_tree[$this->_position];
	}

	private function checkAndInstantTree()
	{
		if (empty($this->_tree))
			$this->getObjectTree();
		return $this;
	}

	function key() {
		return $this->_position;
	}

	function next() {
		++$this->_position;
	}

	function valid() {
		$this->checkAndInstantTree();
		return isset($this->_tree[$this->_position]);
	}
	/* End: Iterator Methods */
}

class TreeNodeWrapper
{
	private $_childs;
	private $_object;

	public function __construct($nodeArray)
	{
		$this->setObject($nodeArray)->setChilds($nodeArray);
	}

	private function setObject($nodeArray)
	{
		if (is_object($nodeArray['object'])){
			$this->_object = $nodeArray['object'];
			return $this;
		}
		throw new \Exception('Object is not instant in '.get_class($this).'!');
	}

	private function setChilds($nodeArray)
	{
		if (isset($nodeArray['childs'])){
			$this->_childs = $nodeArray['childs'];

		}
		return $this;
	}

	public function __call($methodName, $arguments)
	{
		if (method_exists($this, $methodName))
			return call_user_func_array(array($this, $methodName), $arguments);
		else
			return call_user_func_array(array($this->_object, $methodName), $arguments);
	}

	public function __get($varName)
	{
		if ($varName == 'childs') {
			return (empty($this->_childs)) ? null : $this->_childs;
		}
		return $this->_object->$varName;
	}

	public function __set($varName, $varValue)
	{
		$this->_object->$varName = $varValue;
	}
}