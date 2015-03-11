<?php
namespace core\modules\groups;
class Groups extends \core\modules\base\ModuleDecorator
{
	function __construct()
	{
		$object = new GroupsObject;
		$object = new \core\modules\statuses\StatusesDecorator($object);
		parent::__construct($object);
	}
}