<?php
namespace modules\managers\lib;
class ManagersDecorator extends \core\modules\base\ModuleDecorator
{
	private $managers;

	function __construct($object)
	{
		\core\modules\base\ModuleDecorator::__construct($object);
	}

	function instantManagers($partnerId)
	{
		$managers = new Managers();
		$managers->setSubquery(' AND `partnerId` = '. $partnerId);
		return $this->managers = $managers;
	}
	
	function getManagers()
	{
		if (!isset($this->managers)) {
			$managers = new Managers();
			$managers->setSubquery(' AND `partnerId` = '.$this->id);
			$this->managers = $managers;
		}
		return $this->managers;
	}

}