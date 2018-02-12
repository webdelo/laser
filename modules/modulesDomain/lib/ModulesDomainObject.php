<?php
namespace modules\modulesDomain\lib;
class ModulesDomainObject extends \core\modules\base\ModuleObjects
{
	protected $configClass     = '\modules\modulesDomain\lib\ModuleDomainConfig';
	protected $objectClassName = '\modules\modulesDomain\lib\ModuleDomain';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
