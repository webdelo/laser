<?php
namespace core\modules\rights;
class Right extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId)
	{
		$object = new RightObject($objectId);
		$object = new \core\modules\base\ParentDecorator($object);
		parent::__construct($object);
	}
}