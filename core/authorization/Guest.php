<?php
namespace core\authorization;
class Guest
{
	use \core\traits\ObjectPool;
	
	public function getLogin()
	{
		return 'Guest';
	}
	
	public function getGroup()
	{
		return 'Guests';
	}
	
	public function getStatus()
	{
		return $this->getObject('Noop');
	}
	
	public function __call($name, $args)
	{
		return null;
	}
	
	public function __get($name)
	{
		return null;
	}
}