<?php
namespace modules\favorites\lib;
class Favorites implements \ArrayAccess
{
	private $sessionKey = 'favorites';

	public function add( $objectId )
	{
		$_SESSION[$this->sessionKey][$objectId] = $objectId;
		return true;
	}
	
	public function remove($id)
	{
		unset($_SESSION[$this->sessionKey][$id]);
		return true;
	}
	
	public function reset()
	{
		unset($_SESSION[$this->sessionKey]);
	}
//
//	/* Start: Iterator Methods */
//	function rewind() {
//		reset($_SESSION[$this->sessionKey]);
//	}
//
//	function current() {
//		return current($_SESSION[$this->sessionKey]);
//	}
//
//	function key() {
//		return key($_SESSION[$this->sessionKey]);
//	}
//
//	function next() {
//		next($_SESSION[$this->sessionKey]);
//	}
//
//	function valid() {
//		return !!(current($_SESSION[$this->sessionKey]));
//	}
//	/* End: Iterator Methods */
//
//	/* Start: Countable Methods */
//	public function count()
//	{
//		return count($_SESSION[$this->sessionKey]);
//	}
//	/* End: Countable Methods */
//	
	public function isFavorite($id)
	{
		return isset($_SESSION[$this->sessionKey][$id]);
	}
	
	public function offsetExists($offset)
	{
		return isset($_SESSION[$this->sessionKey][$offset]);
	}

	public function offsetGet($offset)
	{
		return $this->offsetExists($offset) ? $_SESSION[$this->sessionKey][$offset] : null;
	}

	public function offsetSet($offset, $value)
	{
		if (is_null($offset)) {
			$_SESSION[$this->sessionKey][] = $value;
		} else {
			$_SESSION[$this->sessionKey][$offset] = $value;
		}
	}

	public function offsetUnset($offset)
	{
		unset($_SESSION[$this->sessionKey][$offset]);
	}
	
	public function getAsArray()
	{
		return isset($_SESSION[$this->sessionKey]) ? $_SESSION[$this->sessionKey] : array();
	}
	
	public function count()
	{
		return count( $this->getAsArray() );
	}
}