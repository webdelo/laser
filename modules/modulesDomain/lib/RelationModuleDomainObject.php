<?php
namespace modules\modulesDomain\lib;
class RelationModuleDomainObject extends \core\modules\base\ModuleRelations
{
	protected $configClass = '\modules\modulesDomain\lib\RelationModuleDomainConfig';

	function __construct($ownerId, $configObject)
	{
		parent::__construct($ownerId, new $this->configClass($configObject));
	}

}