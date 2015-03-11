<?php
namespace modules\catalog\availability\automaticUpdates;
abstract class BaseParser implements \Iterator
{
	protected $availabiliteUpdateGoods = array();
	protected $partnerId;
	
	abstract public function getPartnerId();

	/* Start: Iterator Methods */
	function rewind() 
	{
		reset($this->availabiliteUpdateGoods);
	}

	function current() 
	{
		return current($this->availabiliteUpdateGoods);
	}

	function key() 
	{
		return key($this->availabiliteUpdateGoods);
	}

	function next() 
	{
		next($this->availabiliteUpdateGoods);
	}

	function valid() 
	{
		return !!(current($this->availabiliteUpdateGoods));
	}
	/* End: Iterator Methods */
}