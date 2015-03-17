<?php
namespace modules\cabinet\lib;
class CabinetMenu implements \Iterator, \Countable, \ArrayAccess
{
	
	public $menu = array(
		'realties'      => 'Мои объявления',
		'bookings'      => 'Бронирования',
//		'dialogs'       => 'Сообщения',
//		'reviews'       => 'Отзывы',
		'trips'         => 'Мои поездки',
//		'invoices'      => 'Счета на оплату',
//		'authorization' => 'Авторизация',
		'profile'       => 'Профиль'
	);

	/* Start: Iterator Methods */
	function rewind() {
		reset($this->menu);
	}
	
	public function countTotal()
	{
		return $this->countMessages() + $this->countNotifications();
	}

	public function countNotifications()
	{
		$count = 0;
		foreach ( $this->menu as $menuItem=>$value ) {
			$count += $this->getObject($menuItem)->countNotifications();
		}
		return $count;
	}

	public function countMessages()
	{
		$count = 0;
		foreach ( $this->menu as $menuItem=>$value ) {
			$count += $this->getObject($menuItem)->countMessages();
		}
		return $count;
	}


	function current() {
		return $this->getObject(array_search(current($this->menu), $this->menu));
	}

	private function getObject($menuItem) {
		$className = '\modules\cabinet\lib\Cabinet'.ucfirst($menuItem);
		return new $className($this->menu[$menuItem]);
	}


	function key() {
		return key($this->menu);
	}

	function next() {
		next($this->menu);
	}

	function valid() {
		return !!(current($this->menu));
	}
	/* End: Iterator Methods */

	/* Start: Countable Methods */
	public function count()
	{
		return count($this->menu);
	}
	/* End: Countable Methods */

	/* Start: ArrayAccess Methods */
	public function offsetExists($offset)
	{
		return isset($this->menu[$offset]);
	}

	public function offsetGet($offset)
	{
		return $this->offsetExists($offset) ? $this->menu[$offset] : null;
	}

	public function offsetSet($offset, $value)
	{
		if (is_null($offset)) {
			$this->menu[] = $value;
		} else {
			$this->menu[$offset] = $value;
		}
	}

	public function offsetUnset($offset)
	{
		unset($this->menu[$offset]);
	}
	/* End: ArrayAccess Methods */

}