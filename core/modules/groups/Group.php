<?php
namespace core\modules\groups;
class Group extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new GroupObject($objectId);
		$object = new \core\modules\rights\RightsListDecorator($object);
		parent::__construct($object);
	}

	public function remove () {
		return ($this->delete()) ? (int)$this->id : false ;
	}
}