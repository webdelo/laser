<?php
namespace modules\modulesDomain\lib;
class RelationModuleDomain extends \core\modules\base\ModuleDecorator
{
	function __construct($objectId, $configObject)
	{
		$object = new RelationModuleDomainObject($objectId, $configObject);
		parent::__construct($object);
	}
}