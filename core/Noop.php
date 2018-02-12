<?php
namespace core;
class Noop implements \ArrayAccess
{
	use traits\ObjectPool;
	
	private $data = array();
	
	public function __get($variable)
	{
		return null;
	}
	
	public function __call($function, $args)
	{
		return $this->getNoop();
	}
	
	public function __toString()
	{
		return '';
	}
	
	public function offsetSet($offset, $value) {
		if (!empty($value))
			$this->data[$offset] = $value;
    }
    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }
    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }
    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }
	
	public function count()
	{
		return 0;
	}

}