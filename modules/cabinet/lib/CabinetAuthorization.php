<?php
namespace modules\cabinet\lib;
class CabinetAuthorization extends CabinetBase
{
	use \core\traits\controllers\Authorization;
	
	public function exists()
	{
		return true;
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