<?php
namespace modules\parameters\lib;
class ParametersRelation extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId, $configObject)
	{
		$object = new ParametersRelationObject($objectId, $configObject);
		parent::__construct($object);
	}
}