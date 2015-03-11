<?php
namespace core\modules\relations;
class RelationObject extends \core\modules\base\ModuleObject
{	
	function __construct($objectId, $configObject)
	{
		parent::__construct($objectId, new $configObject($configObject));
	}
}