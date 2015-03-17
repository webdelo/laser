<?php
namespace modules\cabinet\lib;
class CabinetRealties extends CabinetBase
{
	use \core\traits\controllers\Authorization;
	
	public function count()
	{
		return $this->getObjects()->count();
	}
	
	public function getObjects()
	{
		return $this->getAuthorizatedUser()->getRealties();
	}
	
	public function exists()
	{
		return $this->count() > 0;
	}
	
	public function countNotifications()
	{	
		return 0;
	}
	
	public function countMessages()
	{	
		return 0;
	}
}