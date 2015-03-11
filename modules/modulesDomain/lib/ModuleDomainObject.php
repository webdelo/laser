<?php
namespace modules\modulesDomain\lib;
class ModuleDomainObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\modulesDomain\lib\ModuleDomainConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
}