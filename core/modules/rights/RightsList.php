<?php
namespace core\modules\rights;
class RightsList extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId, $configObject)
	{
		$object = new RightsListObject($objectId, $configObject);
		parent::__construct($object);
	}
}