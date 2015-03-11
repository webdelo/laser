<?php
namespace core;
class ArrayWrapper implements \Iterator, \Countable, \Serializable, \ArrayAccess
{
	private $_array = array();
	private $position = 0;

	public function __construct($array)
	{
		$this->_array = $array;
	}

	public function __set ($name, $value)
	{
		$this->_array[$name] = $value;
	}

	public function __get ($name)
	{
		if (isset($this->_array[$name])) {
			if (is_array($this->_array[$name]))
				return new ArrayWrapper($this->_array[$name]);
			else
				return $this->_array[$name];
		}
	}

	/* Start: Iterator Methods */
	function rewind() 
	{
		reset($this->_array);
	}

	function current() 
	{
		return current($this->_array);
	}

	function key() 
	{
		return key($this->_array);
	}

	function next() 
	{
		next($this->_array);
	}

	function valid() 
	{
		return !!(current($this->_array));
	}
	/* End: Iterator Methods */
	
	public function offsetExists($offset)
    {
        return isset($this->_array[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->_array[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->_array[] = $value;
        } else {
            $this->_array[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->_array[$offset]);
    }

	public function count()
	{
		return count($this->_array);
	}

	public function serialize()
    {
        return serialize($this->_array);
    }

    public function unserialize($data)
    {
        $this->_array = unserialize($data);
    }

	public function getArray () 
	{
		return $this->_array;
	}
}