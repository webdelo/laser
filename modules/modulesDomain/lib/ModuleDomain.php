<?php
namespace modules\modulesDomain\lib;
class ModuleDomain extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new ModuleDomainObject($objectId);
		$object = new \core\modules\categories\CategoryDecorator($object);
		$object = new \core\modules\statuses\StatusDecorator($object);
		parent::__construct($object);
	}

	public function remove () {
		return ($this->delete()) ? (int)$this->id : false ;
	}
}