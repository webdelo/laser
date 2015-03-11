<?php
namespace core\modules\base;
abstract class ModuleDecorator implements \Iterator, \ArrayAccess
{
	use \core\traits\ObjectPool;

	private $_object = null;
	private $objectId;
	private $objectConfig;

	function __construct($_object)
	{
		$this->setObject($_object)->setObjectConfig();
	}

	protected  function setObject($_object)
	{
		$this->checkObject($_object);
		$this->_object = $_object;
		return $this;
	}

	protected function checkObject($object)
	{
		if (!is_object($object))
			throw new \Exception('Object has not been received in '.get_class($this).' Object.');
		return true;
	}

	protected function setObjectId($objectId)
	{
		$this->objectId = $objectId;
		return $this;
	}

	protected function getObjectId()
	{
		return $this->objectId;
	}

	protected function setObjectConfig($objectConfig = null)
	{
		$this->objectConfig = ($objectConfig) ? $objectConfig : $this->_object->getConfig();
		return $this;
	}

	protected function getObjectConfig()
	{
		return $this->objectConfig;
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
		return (property_exists($this,$varName)) ? $this->$varName : $this->_object->$varName;
	}

	public function __set($varName, $varValue)
	{
		(property_exists($this,$varName)) ? $this->$varName = $varValue : $this->_object->$varName = $varValue;
	}

	public function __isset($name)
    {
		if (property_exists($this,$name))
			return isset($this->$name);
        return isset($this->_object->$name);
    }

	public function __toString()
	{
		return (string)$this->_object;
	}

	protected function getParentObject()
	{
		return $this->_object;
	}

	/* Start: Iterator Methods */
	function rewind()
	{
		$this->getParentObject()->rewind();
	}

	function current()
	{
		return $this->getParentObject()->current();
	}

	function key()
	{
		return $this->getParentObject()->key();
	}

	function next()
	{
		$this->getParentObject()->next();
	}

	function valid()
	{
		return $this->getParentObject()->valid();
	}
	/* End: Iterator Methods */

	public function offsetExists($offset)
    {
        return $this->getParentObject()->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
		return $this->getParentObject()->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
		$this->getParentObject()->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
		$this->getParentObject()->offsetUnset($offset);
    }

}